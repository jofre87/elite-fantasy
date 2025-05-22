<?php

namespace App\Http\Controllers;

use App\Models\EquiposUsuarioJornada;
use App\Models\Liga;
use App\Models\JugadorUserLiga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlineacionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $ligaId = session('liga_activa');

        // Obtener la liga del usuario
        $liga = Liga::where('id', $ligaId)
            ->whereHas('usuarios', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->first();

        // Obtener los jugadores activos y suplentes SOLO de la liga activa
        $activos = JugadorUserLiga::with(['jugador.equipo', 'jugador.estadisticasTemporada'])
            ->where('user_id', $user->id)
            ->where('liga_id', $ligaId)
            ->where('en_once_inicial', true)
            ->get();

        $noActivos = JugadorUserLiga::with(['jugador.equipo', 'jugador.estadisticasTemporada'])
            ->where('user_id', $user->id)
            ->where('liga_id', $ligaId)
            ->where('en_once_inicial', false)
            ->get();

        return view('alineacion', compact('activos', 'noActivos', 'liga'));
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

        if (is_null($activoId)) {
            // Caso hueco vacío: solo damos de alta al suplente
            $jugadorSuplente->en_once_inicial = 1;
            $jugadorSuplente->save();

            return redirect()->route('alineacion.index')
                ->with('success', 'Jugador agregado al once correctamente.');
        }

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

    public function desalinear(Request $request)
    {
        $activoId = $request->input('activo_id');

        // Buscar el jugador activo en la tabla jugador_user_liga
        $jugadorActivo = JugadorUserLiga::where('user_id', auth()->id())
            ->where('jugador_id', $activoId)
            ->first();

        if ($jugadorActivo) {
            // Cambiar el estado a no activo
            $jugadorActivo->en_once_inicial = 0;
            $jugadorActivo->save();

            return redirect()->route('alineacion.index')->with('success', 'Jugador desalineado correctamente.');
        }

        return redirect()->route('alineacion.index')->with('error', 'Error al desalinear el jugador.');
    }
}
