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

        // Verificar si es admin
        $liga = Liga::where('administrador_id', $user->id)
            ->where('id', $ligaId)
            ->first();

        if (!$liga) {
            return redirect()->back()->with('error', 'No tienes permisos para iniciar la jornada.');
        }

        if ($liga->jornada_activa) {
            return redirect()->back()->with('error', 'La jornada ya está activa.');
        }

        // Calcular la nueva jornada
        $ultimoJornadaId = EquiposUsuarioJornada::where('liga_id', $ligaId)->max('jornada_id');
        $nuevoJornadaId = $ultimoJornadaId ? $ultimoJornadaId + 1 : 1;

        // Recorrer todos los usuarios de la liga y guardar su once actual
        $usuarios = LigaUser::where('liga_id', $ligaId)->pluck('user_id');

        foreach ($usuarios as $usuarioId) {
            $activos = JugadorUserLiga::where('user_id', $usuarioId)
                ->where('liga_id', $ligaId)
                ->where('en_once_inicial', true)
                ->get();

            foreach ($activos as $jugador) {
                EquiposUsuarioJornada::create([
                    'user_id' => $usuarioId,
                    'liga_id' => $ligaId,
                    'jornada_id' => $nuevoJornadaId,
                    'jugador_id' => $jugador->jugador_id,
                    'puntos' => 0,
                    'posicion' => $jugador->jugador->posicion,
                    'created_at' => now(),
                ]);
            }
        }

        $liga->update(['jornada_activa' => true]);

        return redirect()->back()->with('success', 'Jornada iniciada correctamente.');
    }

    public function finalizar(Request $request)
    {

        $user = Auth::user();
        $ligaId = session('liga_activa');

        $liga = Liga::where('administrador_id', $user->id)
            ->where('id', $ligaId)
            ->first();

        if (!$liga || !$liga->jornada_activa) {
            return redirect()->back()->with('error', 'No tienes permisos o no hay jornada activa.');
        }

        $ultimoJornadaId = EquiposUsuarioJornada::where('liga_id', $ligaId)->max('jornada_id');

        $equipos = EquiposUsuarioJornada::where('liga_id', $ligaId)
            ->where('jornada_id', $ultimoJornadaId)
            ->get();

        // 1. Guardar el count de racha_puntos antes del scraping
        $countsAntes = [];
        foreach ($equipos as $equipo) {
            $rachaAntes = $equipo->jugador->estadisticasTemporada->racha_puntos ?? null;
            $countsAntes[$equipo->id] = is_array(json_decode($rachaAntes, true)) ? count(json_decode($rachaAntes, true)) : 0;
        }

        // 2. Hacer scraping
        (new ScrapingController())->scrapePlayers();

        //CODIGO MALO// Comentar cuando empiece la liga en la vida real para que funcione correctamente
        foreach ($equipos as $equipo) {
            $rachaPuntos = $equipo->jugador->estadisticasTemporada->racha_puntos ?? null;
            $primerValor = 0;

            if ($rachaPuntos) {
                $array = json_decode($rachaPuntos, true);
                if (is_array($array) && count($array)) {
                    $primerValor = $array[0];
                }
            }

            $equipo->update(['puntos' => $primerValor]);
        }

        //CODIGO BUENO//Descomentar cuando empiece la liga en la vida real para que funcione correctamente
        /*
        foreach ($equipos as $equipo) {
            $rachaPuntos = $equipo->jugador->estadisticasTemporada->racha_puntos ?? null;
            $array = is_array(json_decode($rachaPuntos, true)) ? json_decode($rachaPuntos, true) : [];
            $countAntes = $countsAntes[$equipo->id] ?? 0;
            $countDespues = count($array);

            if ($countDespues > $countAntes) {
                // Jugador convocado: asignar el último valor añadido
                $puntos = $array[$countDespues - 1];
            } else {
                // No convocado: asignar 0
                $puntos = 0;
            }

            $equipo->update(['puntos' => $puntos]);
        }
            */

        // Calcular y asignar puntos y saldo
        $usuariosLiga = LigaUser::where('liga_id', $ligaId)->get();

        foreach ($usuariosLiga as $usuarioLiga) {
            $puntosActuales = $usuarioLiga->puntos_totales;

            $puntosNuevos = EquiposUsuarioJornada::where('liga_id', $ligaId)
                ->where('user_id', $usuarioLiga->user_id)
                ->where('jornada_id', $ultimoJornadaId)
                ->sum('puntos');

            $cantidadActivos = EquiposUsuarioJornada::where('liga_id', $ligaId)
                ->where('user_id', $usuarioLiga->user_id)
                ->where('jornada_id', $ultimoJornadaId)
                ->count();

            $penalizacion = (11 - $cantidadActivos) * -4;
            $puntosNuevos += $penalizacion;

            if ($puntosNuevos > 0) {
                $usuarioLiga->update([
                    'saldo' => $usuarioLiga->saldo + ($puntosNuevos * 100000)
                ]);
            }

            $usuarioLiga->update([
                'puntos_totales' => $puntosActuales + $puntosNuevos
            ]);
        }

        $liga->update(['jornada_activa' => false]);

        return redirect()->back()->with('success', 'Jornada finalizada correctamente.');
    }
}
