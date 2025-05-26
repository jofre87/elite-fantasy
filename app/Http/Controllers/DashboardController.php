<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LigaUser;
use App\Models\Jugador;
use App\Models\EquiposUsuarioJornada;
use App\Models\JugadorUserLiga;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        //Obtener y mantener la liga activa correctamente
        $ligaId = $request->input('liga_id');

        if ($ligaId) {
            session(['liga_activa' => $ligaId]);
        } else {
            $ligaId = session('liga_activa');

            if (!$ligaId) {
                $ligaId = LigaUser::where('user_id', $user->id)
                    ->select('liga_id')
                    ->orderBy('liga_id')
                    ->first()?->liga_id;

                session(['liga_activa' => $ligaId]);
            }
        }

        if (!$ligaId) {
            return view('dashboard-vacio'); // No estás en ninguna liga
        }

        // Obtener usuarios de esa liga
        $ligaUsers = LigaUser::where('liga_id', $ligaId)
            ->with('user')
            ->orderByDesc('saldo')
            ->get();

        $ligaUser = LigaUser::with('user')
            ->where('user_id', $user->id)
            ->where('liga_id', $ligaId)
            ->first();

        // Obtener jornadas jugadas por este usuario
        $jornadas = EquiposUsuarioJornada::where('liga_id', $ligaId)
            ->where('user_id', $user->id)
            ->select('jornada_id')
            ->distinct()
            ->orderBy('jornada_id')
            ->get()
            ->pluck('jornada_id');

        $jornadaSeleccionada = $request->input('jornada_id', $jornadas->last());

        // Alineación del usuario en la jornada seleccionada
        $alineacion = EquiposUsuarioJornada::with('jugador.equipo')
            ->where('user_id', $user->id)
            ->where('liga_id', $ligaId)
            ->where('jornada_id', $jornadaSeleccionada)
            ->get();

        // Jugadores con estadísticas
        $jugadores = Jugador::with('equipo', 'estadisticasTemporada')->get();

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

        return view('dashboard', compact(
            'ligaUsers',
            'ligaUser',
            'ligaId',
            'jugadores',
            'jornadas',
            'jornadaSeleccionada',
            'alineacion',
            'valorMercadoTotal',
            'valorMercadoDiferencia',
        ))->with('title', env('APP_TITLE', 'ELITEFANTASY'));
    }
}
