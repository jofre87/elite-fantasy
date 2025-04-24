<?php

namespace App\Http\Controllers;

use App\Models\Liga;
use App\Models\LigaUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UnirteLiga extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'league_code' => ['required', 'regex:/^[A-Z0-9]{3}-[A-Z0-9]{3}$/'],
            'password' => ['required', 'string'],
        ]);

        $code = $request->input('league_code');
        $password = $request->input('password');

        // Buscar liga por código
        $liga = Liga::where('id', $code)->first();

        if (!$liga) {
            return redirect()->back()->withErrors(['league_code' => 'Liga no encontrada.']);
        }

        if (!Hash::check($password, $liga->password)) {
            return redirect()->back()->withErrors(['password' => 'Contraseña incorrecta.']);
        }

        // Verificar si el usuario ya está en la liga
        $exists = LigaUser::where('liga_id', $liga->id)
            ->where('user_id', auth()->id())
            ->exists(); // Verifica si ya existe una relación entre el usuario y la liga

        if (!$exists) {
            LigaUser::create([
                'liga_id' => $liga->id, // Ahora usamos el ID generado automáticamente
                'user_id' => auth()->id(),
                'saldo' => $liga->saldo_inicial,
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Te has unido a la liga correctamente.');
    }
}
