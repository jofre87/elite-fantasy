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
        $ligaId = session('liga_activa');

        // Verificar si el usuario es administrador de la liga
        $liga = Liga::where('administrador_id', $user->id)
            ->where('id', $ligaId)
            ->first();

        if (!$liga) {
            return redirect()->back()->with('error', 'No tienes permisos para iniciar la jornada.');
        }

        // Verificar si la jornada ya está activa
        if ($liga->jornada_activa) {
            return redirect()->back()->with('error', 'La jornada ya está activa.');
        }

        // Calcular el próximo número de jornada
        $ultimoJornadaId = EquiposUsuarioJornada::where('liga_id', $liga->id)
            ->max('jornada_id'); // Obtener el valor más alto de jornada_id
        $nuevoJornadaId = $ultimoJornadaId ? $ultimoJornadaId + 1 : 1; // Si no hay datos, asignar 1

        // Guardar el equipo activo en la tabla equipos_usuario_jornada
        $activos = JugadorUserLiga::where('user_id', $user->id)
            ->where('liga_id', $ligaId)
            ->where('en_once_inicial', true)
            ->get();

        foreach ($activos as $jugador) {
            EquiposUsuarioJornada::create([
                'user_id' => $user->id,
                'liga_id' => $liga->id,
                'jornada_id' => $nuevoJornadaId, // Usar un identificador único para la jornada
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
        // Llamar al método scrapePlayers para actualizar los datos
        $scrapingController = new ScrapingController();
        $scrapingController->scrapePlayers();

        $user = Auth::user();
        $ligaId = session('liga_activa');

        // Verificar si el usuario es administrador de la liga
        $liga = Liga::where('administrador_id', $user->id)
            ->where('id', $ligaId)
            ->first();

        if (!$liga) {
            return redirect()->back()->with('error', 'No tienes permisos para finalizar la jornada.');
        }

        // Verificar si la jornada está activa
        if (!$liga->jornada_activa) {
            return redirect()->back()->with('error', 'No hay una jornada activa para finalizar.');
        }

        // Asignar la última puntuación de cada jugador
        $ultimoJornadaId = EquiposUsuarioJornada::where('liga_id', $liga->id)
            ->max('jornada_id'); // Obtener el último identificador de jornada

        $equipos = EquiposUsuarioJornada::where('liga_id', $liga->id)
            ->where('jornada_id', $ultimoJornadaId) // Usar el último identificador de jornada
            ->get();

        foreach ($equipos as $equipo) {
            $rachaPuntos = $equipo->jugador->estadisticasTemporada->racha_puntos ?? null;
            $primerValor = $rachaPuntos ? json_decode($rachaPuntos, true)[0] : 0;
            $equipo->update([
                'puntos' => $primerValor,
            ]);
        }

        // Calcular y asignar el total de puntos en la tabla liga_user
        $usuariosLiga = LigaUser::where('liga_id', $liga->id)->get();

        foreach ($usuariosLiga as $usuarioLiga) {
            $puntosActuales = $usuarioLiga->puntos_totales; // Obtener los puntos actuales

            $puntosNuevos = EquiposUsuarioJornada::where('liga_id', $liga->id)
                ->where('user_id', $usuarioLiga->user_id)
                ->where('jornada_id', $ultimoJornadaId)
                ->sum('puntos');

            $cantidadActivos = EquiposUsuarioJornada::where('liga_id', $liga->id)
                ->where('user_id', $usuarioLiga->user_id)
                ->where('jornada_id', $ultimoJornadaId)
                ->count();

            // Calcular la penalización por jugadores faltantes
            $penalizacion = (11 - $cantidadActivos) * -4;

            $puntosNuevos += $penalizacion; // Sumar la penalización a los nuevos puntos

            if ($puntosNuevos > 0) {
                $dineroActual = $usuarioLiga->saldo;
                $dineroGanado = $puntosNuevos * 100000; // Convertir a la unidad de saldo
                $usuarioLiga->update(['saldo' => $dineroGanado + $dineroActual]); // Actualizar el saldo
            }

            $usuarioLiga->update(['puntos_totales' => $puntosActuales + $puntosNuevos]); // Sumar los nuevos puntos a los actuales
        }

        // Marcar la jornada como inactiva
        $liga->update(['jornada_activa' => false]);

        return redirect()->back()->with('success', 'Jornada finalizada correctamente.');
    }
}