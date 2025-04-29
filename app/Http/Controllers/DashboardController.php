<?php

namespace App\Http\Controllers;

use App\Models\LigaUser;
use App\Models\Jugador;

class DashboardController extends Controller
{
    public function index()
    {
        // Clasificación ordenada por saldo
        $ligaUsers = LigaUser::with('user')->orderByDesc('saldo')->get();

        // Todos los jugadores con equipo y estadísticas
        $jugadores = Jugador::with('equipo', 'estadisticasTemporada')->get();

        // Top 10 goleadores
        $goleadores = $jugadores->sortByDesc(fn($j) => $j->estadisticasTemporada->goles ?? 0)->take(10);

        // Pasar datos a la vista
        return view('dashboard', compact('ligaUsers', 'goleadores'));
    }
}
