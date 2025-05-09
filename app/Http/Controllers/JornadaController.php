<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Liga;
use App\Models\EquiposUsuarioJornada;
use App\Models\JugadorUserLiga;
use App\Models\LigaUser;

class JornadaController extends Controller
{
    public function iniciar(Request $request)
    {
        $user = Auth::user();

        // Verificar si el usuario es administrador de la liga
        $liga = Liga::where('administrador_id', $user->id)->first();

        if (!$liga) {
            return redirect()->back()->with('error', 'No tienes permisos para iniciar la jornada.');
        }

        // Verificar si la jornada ya está activa
        if ($liga->jornada_activa) {
            return redirect()->back()->with('error', 'La jornada ya está activa.');
        }

        // Guardar el equipo activo en la tabla equipos_usuario_jornada
        $activos = JugadorUserLiga::where('user_id', $user->id)
            ->where('en_once_inicial', true)
            ->get();

        foreach ($activos as $jugador) {
            EquiposUsuarioJornada::create([
                'user_id' => $user->id,
                'liga_id' => $liga->id,
                'jornada_id' => now()->timestamp, // Usar un identificador único para la jornada
                'jugador_id' => $jugador->jugador_id,
                'puntos' => 0, // Inicialmente 0
                'posicion' => $jugador->jugador->posicion,
                'created_at' => now(),
            ]);
        }

        // Marcar la jornada como activa
        $liga->update(['jornada_activa' => true]);

        return redirect()->back()->with('success', 'Jornada iniciada correctamente.');
    }

    public function finalizar(Request $request)
    {
        $user = Auth::user();

        // Verificar si el usuario es administrador de la liga
        $liga = Liga::where('administrador_id', $user->id)->first();

        if (!$liga) {
            return redirect()->back()->with('error', 'No tienes permisos para finalizar la jornada.');
        }

        // Verificar si la jornada está activa
        if (!$liga->jornada_activa) {
            return redirect()->back()->with('error', 'No hay una jornada activa para finalizar.');
        }

        // Asignar la última puntuación de cada jugador
        $equipos = EquiposUsuarioJornada::where('liga_id', $liga->id)
            ->where('jornada_id', now()->timestamp) // Usar el mismo identificador de jornada
            ->get();

        foreach ($equipos as $equipo) {
            $equipo->update([
                'puntos' => $equipo->jugador->estadisticasTemporada->puntos_totales ?? 0,
            ]);
        }

        // Calcular y asignar el total de puntos en la tabla liga_user
        $usuariosLiga = LigaUser::where('liga_id', $liga->id)->get();

        foreach ($usuariosLiga as $usuarioLiga) {
            $totalPuntos = EquiposUsuarioJornada::where('liga_id', $liga->id)
                ->where('user_id', $usuarioLiga->user_id)
                ->sum('puntos');

            $usuarioLiga->update(['puntos_totales' => $totalPuntos]);
        }

        // Marcar la jornada como inactiva
        $liga->update(['jornada_activa' => false]);

        return redirect()->back()->with('success', 'Jornada finalizada correctamente.');
    }
}