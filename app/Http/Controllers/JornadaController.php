<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Liga;

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

        // Lógica para iniciar la jornada
        // Por ejemplo, marcar la jornada como activa en la base de datos
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

        // Lógica para finalizar la jornada
        // Por ejemplo, marcar la jornada como inactiva en la base de datos
        $liga->update(['jornada_activa' => false]);

        return redirect()->back()->with('success', 'Jornada finalizada correctamente.');
    }
}