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
        $user = Auth::user();
        $hoy = Carbon::today();
        $horaActual = Carbon::now()->format('H:i');

        // Verifica si ya se generó mercado hoy
        $mercadoHoy = Mercado::whereDate('fecha', $hoy)
            ->with('jugador.equipo', 'jugador.estadisticasTemporada')
            ->get();

        if ($mercadoHoy->isEmpty() && $horaActual >= '07:00') {
            Mercado::truncate();

            // Selecciona jugadores aleatorios con valor_actual > 0
            $jugadoresAleatorios = Jugador::where('valor_actual', '>', 0)
                ->whereHas('estadisticasTemporada', function ($q) {
                    $q->where('puntos_totales', '>', 0);
                })
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

        // Aseguramos que las estadísticas de cada jugador estén disponibles
        foreach ($mercadoHoy as $jugador) {
            if ($jugador->estadisticasTemporada) {
                $jugador->estadisticasTemporada->makeHidden(['id', 'jugador_id', 'created_at', 'updated_at']);
            }
        }

        // Obtener la liga del usuario para mostrar saldo y puntos
        $ligaId = session('liga_activa');
        $ligaUser = LigaUser::with('user')
            ->where('user_id', $user->id)
            ->where('liga_id', $ligaId)
            ->first();

        // Obtener los jugadores activos y suplentes SOLO de la liga activa
        $misJugadores = JugadorUserLiga::with(['jugador.equipo', 'jugador.estadisticasTemporada'])
            ->where('user_id', $user->id)
            ->where('liga_id', $ligaId)
            ->get();

        $valorMercadoTotal = $misJugadores->sum(function ($jugadorUserLiga) {
            return $jugadorUserLiga->jugador->valor_actual ?? 0;
        });

        $valorMercadoDiferencia = $misJugadores->sum(function ($jugadorUserLiga) {
            return $jugadorUserLiga->jugador->diferencia ?? 0;
        });

        return view('mercado', [
            'players' => $mercadoHoy,
            'ligaUser' => $ligaUser,
            'valorMercadoTotal' => $valorMercadoTotal,
            'valorMercadoDiferencia' => $valorMercadoDiferencia,
        ]);
    }

    public function comprarJugador(Request $request, $jugadorId)
    {
        $user = Auth::user();
        $jugador = Jugador::findOrFail($jugadorId);
        $ligaId = session('liga_activa');

        $ligaUser = LigaUser::where('user_id', $user->id)
            ->where('liga_id', $ligaId)
            ->firstOrFail();

        // Validar saldo suficiente
        if ($ligaUser->saldo < $jugador->valor_actual) {
            return back()->with('error', 'No tienes saldo suficiente para comprar este jugador.');
        }

        // Verificar si ya tiene al jugador en la liga
        $yaTieneJugador = JugadorUserLiga::where('user_id', $user->id)
            ->where('liga_id', $ligaUser->liga_id)
            ->where('jugador_id', $jugadorId)
            ->exists();

        if ($yaTieneJugador) {
            return back()->with('error', 'Ya tienes este jugador en tu equipo.');
        }

        // Descontar saldo
        $ligaUser->saldo -= $jugador->valor_actual;
        $ligaUser->save();

        // Agregar el jugador al usuario en la liga
        JugadorUserLiga::create([
            'jugador_id' => $jugadorId,
            'user_id' => $user->id,
            'liga_id' => $ligaUser->liga_id,
            'comprado_en' => Carbon::now(),
            'en_once_inicial' => false,
        ]);

        return back()->with('success', 'Jugador comprado exitosamente.');
    }

    public function venderJugador(Request $request)
    {
        $user = Auth::user();
        $jugadorId = $request->input('jugador_id');
        $jugador = Jugador::findOrFail($jugadorId);
        $ligaId = session('liga_activa');

        $ligaUser = LigaUser::where('user_id', $user->id)
            ->where('liga_id', $ligaId)
            ->firstOrFail();

        // Sumar saldo
        $ligaUser->saldo += $jugador->valor_actual;
        $ligaUser->save();

        // Eliminar el jugador del usuario en la liga
        JugadorUserLiga::where('jugador_id', $jugadorId)
            ->where('user_id', $user->id)
            ->where('liga_id', $ligaId)
            ->delete();

        return back()->with('success', 'Jugador vendido exitosamente.');
    }
}
