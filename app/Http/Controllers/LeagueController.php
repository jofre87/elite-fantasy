<?php

namespace App\Http\Controllers;

use App\Models\Liga;
use App\Models\LigaUser;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LeagueController extends Controller
{
    public function store(Request $request)
    {
        // Valida los datos del formulario
        $validated = $request->validate([
            'league_name' => 'required|string|max:255',
            'password' => 'required|string|max:255', // Validación para la contraseña
            'initial_share' => 'required|numeric|min:0', // Validación para el reparto inicial
        ]);

        // Guarda la liga en la base de datos
        $liga = Liga::create([
            'nombre' => $validated['league_name'],
            'password' => bcrypt($validated['password']),
            'saldo_inicial' => $validated['initial_share'],
            'administrador_id' => auth()->id(),
        ]);

        // Guarda el reparto inicial en la tabla `liga_user`
        LigaUser::create([
            'liga_id' => $liga->id, // Ahora usamos el ID generado automáticamente
            'user_id' => auth()->id(),
            'saldo' => $validated['initial_share'],
        ]);

        // Redirige al usuario con un mensaje de éxito
        return redirect()->route('dashboard')->with('success', 'Liga creada exitosamente.');
    }
}
