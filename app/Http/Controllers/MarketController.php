<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Jugador;
use App\Models\LigaUser;
use App\Models\JugadorUserLiga;
use Illuminate\Http\Request;
use App\Models\Mercado;
use Carbon\Carbon;

class MarketController extends Controller
{
    public function index()
    {
        $hoy = Carbon::today();
        $horaActual = Carbon::now()->format('H:i');

        // Verifica si ya se generó mercado hoy
        $mercadoHoy = Mercado::whereDate('fecha', $hoy)->with('jugador.equipo', 'jugador.estadisticasTemporada')->get();

        if ($mercadoHoy->isEmpty() && $horaActual >= '07:00') {

            Mercado::truncate();

            // Selecciona 20 jugadores aleatorios con valor_actual > 0
            $jugadoresAleatorios = Jugador::where('valor_actual', '>', 0)
                ->inRandomOrder()
                ->limit(15)
                ->get();

            foreach ($jugadoresAleatorios as $jugador) {
                Mercado::create([
                    'jugador_id' => $jugador->id,
                    'fecha' => $hoy,
                ]);
            }

            $mercadoHoy = $jugadoresAleatorios;
        } else {
            // Extraer solo los jugadores si ya estaba generado
            $mercadoHoy = $mercadoHoy->pluck('jugador');
        }

        return view('mercado', ['players' => $mercadoHoy]);
    }

    public function comprarJugador(Request $request, $jugadorId)
    {
        $user = Auth::user();
        $jugador = Jugador::findOrFail($jugadorId);

        // Obtén la liga del usuario (si solo puede estar en una)
        $ligaUser = LigaUser::where('user_id', $user->id)->firstOrFail();

        // Validación: saldo suficiente
        if ($ligaUser->saldo < $jugador->valor_actual) {
            return back()->with('error', 'No tienes saldo suficiente para comprar este jugador.');
        }

        // Verifica que no tenga ya al jugador en la liga
        $yaTieneJugador = JugadorUserLiga::where('user_id', $user->id)
            ->where('liga_id', $ligaUser->liga_id)
            ->where('jugador_id', $jugadorId)
            ->exists();

        if ($yaTieneJugador) {
            return back()->with('error', 'Ya tienes este jugador en tu equipo.');
        }

        // Resta el saldo
        $ligaUser->saldo -= $jugador->valor_actual;
        $ligaUser->save();

        // Agrega el jugador al usuario en la liga
        JugadorUserLiga::create([
            'jugador_id' => $jugadorId,
            'user_id' => $user->id,
            'liga_id' => $ligaUser->liga_id,
            'comprado_en' => Carbon::now(),
        ]);

        return back()->with('success', 'Jugador comprado exitosamente.');
    }
}
