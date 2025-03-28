<?php

namespace App\Http\Controllers;

use App\Models\Jugador;

class DashboardController extends Controller
{
    public function index()
    {
        // Obtener todos los jugadores de la base de datos
        $players = Jugador::all();  // O puedes usar métodos como ->with() si quieres relaciones, o ->where() si necesitas filtrar

        // Pasar los jugadores a la vista
        return view('dashboard', compact('players'));
    }
}
