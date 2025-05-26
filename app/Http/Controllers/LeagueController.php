<?php

namespace App\Http\Controllers;

use App\Models\Liga;
use App\Models\LigaUser;
use Illuminate\Http\Request;
use App\Services\EquipoInicialService;
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
            'saldo_inicial' => $validated['initial_share'] * 1000000,
            'administrador_id' => auth()->id(),
            'jornada_activa' => false,
        ]);

        // Llamar al método scrapePlayers para actualizar los datos
        $scrapingController = new ScrapingController();
        $scrapingController->scrapePlayers();

        $valorRestante = EquipoInicialService::asignarEquipoInicial(auth()->id(), $liga);

        if ($valorRestante === false) {
            return redirect()->back()->withErrors(['jugadores' => 'No hay suficientes jugadores asequibles para formar un equipo inicial.']);
        }

        // Guarda el reparto inicial en la tabla `liga_user`
        LigaUser::create([
            'liga_id' => $liga->id, // Ahora usamos el ID generado automáticamente
            'user_id' => auth()->id(),
            'saldo' => $valorRestante,
        ]);

        // Redirige al usuario con un mensaje de éxito
        return redirect()->route('dashboard')->with('success', 'Liga creada exitosamente.');
    }

    public function salirLiga()
    {
        $user = auth()->user();
        $ligaId = session('liga_activa');

        // Elimina la relación del usuario con la liga activa
        LigaUser::where('user_id', $user->id)
            ->where('liga_id', $ligaId)
            ->delete();

        // Limpia la liga activa de la sesión
        session()->forget('liga_activa');

        return redirect()->route('dashboard')->with('success', 'Has salido de la liga.');
    }
}
