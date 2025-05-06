<?php

namespace App\Http\Controllers;

use App\Models\EquiposUsuarioJornada;
use App\Models\JugadorUserLiga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlineacionController extends Controller
{
    public function index()
    {
        // Obtener los jugadores activos (en_once_inicial = true)
        $activos = JugadorUserLiga::with('jugador.equipo', 'jugador.estadisticasTemporada')
            ->where('user_id', Auth::id())
            ->where('en_once_inicial', true)
            ->get();

        // Obtener los jugadores no activos (en_once_inicial = false)
        $noActivos = JugadorUserLiga::with('jugador.equipo', 'jugador.estadisticasTemporada')
            ->where('user_id', Auth::id())
            ->where('en_once_inicial', false)
            ->get();

        return view('alineacion', compact('activos', 'noActivos'));
    }

    public function intercambiar(Request $request)
    {
        $activoId = $request->input('activo_id');
        $suplenteId = $request->input('suplente_id');

        // Buscar los jugadores en la tabla jugador_user_liga
        $jugadorActivo = JugadorUserLiga::where('user_id', auth()->id())
            ->where('jugador_id', $activoId)
            ->first();

        $jugadorSuplente = JugadorUserLiga::where('user_id', auth()->id())
            ->where('jugador_id', $suplenteId)
            ->first();

        if ($jugadorActivo && $jugadorSuplente) {
            // Intercambiar el estado de en_once_inicial
            $jugadorActivo->en_once_inicial = 0;
            $jugadorSuplente->en_once_inicial = 1;

            // Guardar los cambios
            $jugadorActivo->save();
            $jugadorSuplente->save();

            return redirect()->route('alineacion.index')->with('success', 'Intercambio realizado correctamente.');
        }

        return redirect()->route('alineacion.index')->with('error', 'Error al intercambiar jugadores.');
    }
}
