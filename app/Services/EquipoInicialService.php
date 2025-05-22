<?php

namespace App\Services;

use App\Models\Jugador;
use App\Models\JugadorUserLiga;

class EquipoInicialService
{
    /**
     * Asigna un equipo inicial aleatorio a un usuario en una liga,
     * respetando el saldo máximo.
     * Devuelve el valor total de los jugadores asignados o false si falla.
     */
    public static function asignarEquipoInicial($userId, $liga)
    {
        $valorUnitarioMax = intval($liga->saldo_inicial / 11);

        // 1 portero
        $portero = Jugador::where('posicion', 'Portero')
            ->where('valor_actual', '<=', $valorUnitarioMax)
            ->inRandomOrder()
            ->first();

        // 4 defensas
        $defensas = Jugador::where('posicion', 'Defensa')
            ->where('valor_actual', '<=', $valorUnitarioMax)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        // 3 centrocampistas
        $mediocampista = Jugador::where('posicion', 'Mediocampista')
            ->where('valor_actual', '<=', $valorUnitarioMax)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        // 3 delanteros
        $delanteros = Jugador::where('posicion', 'Delantero')
            ->where('valor_actual', '<=', $valorUnitarioMax)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        // Unir todos los jugadores en una sola colección
        $jugadoresSeleccionados = collect([$portero])
            ->merge($defensas)
            ->merge($mediocampista)
            ->merge($delanteros);


        foreach ($jugadoresSeleccionados as $jugador) {
            JugadorUserLiga::create([
                'jugador_id' => $jugador->id,
                'user_id' => $userId,
                'liga_id' => $liga->id,
                'comprado_en' => now(),
                'en_once_inicial' => 1,
            ]);
        }

        $valorRestante = $liga->saldo_inicial - $jugadoresSeleccionados->sum('valor_actual');

        return $valorRestante;
    }
}