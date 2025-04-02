<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LeagueController extends Controller
{
    public function store(Request $request)
    {
        // Valida los datos del formulario
        $validated = $request->validate([
            'league_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        // Aquí puedes guardar la liga en la base de datos
        // Por ejemplo:
        // League::create($validated);

        // Redirige al usuario con un mensaje de éxito
        return redirect()->route('dashboard')->with('success', 'Liga creada exitosamente.');
    }
}
