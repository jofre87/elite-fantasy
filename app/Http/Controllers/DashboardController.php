<?php

namespace App\Http\Controllers;

use App\Models\LigaUser;
use App\Models\Jugador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $ligaId = $request->input('liga_id');

        // Si no se pasa liga_id, usamos la primera liga en la que participa el usuario
        if (!$ligaId) {
            $ligaId = LigaUser::where('user_id', $user->id)
                ->select('liga_id')
                ->orderBy('liga_id')
                ->first()?->liga_id;

            if (!$ligaId) {
                // El usuario no está en ninguna liga
                return view('dashboard-vacio'); // o redireccionar con un mensaje
            }
        }

        // Guardar la liga activa en sesión
        session(['liga_activa' => $ligaId]);

        // Obtener usuarios de esa liga
        $ligaUsers = LigaUser::where('liga_id', $ligaId)
            ->with('user')
            ->orderByDesc('saldo')
            ->get();

        // Jugadores y goleadores
        $jugadores = Jugador::with('equipo', 'estadisticasTemporada')->get();

        return view('dashboard', compact('ligaUsers', 'ligaId'));
    }
}
