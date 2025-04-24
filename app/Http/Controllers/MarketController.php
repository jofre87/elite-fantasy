<?php

namespace App\Http\Controllers;

use App\Models\Jugador;
use App\Models\Player;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    /**
     * Muestra la vista de mercado con todos los jugadores.
     */
    public function index()
    {
        // Trae todos los jugadores (ajusta la query si quieres paginar, filtrar, etc.)
        $players = Jugador::all();

        // Pasa la colección a la vista resources/views/market.blade.php
        return view('mercado', compact('players'));
    }
}
