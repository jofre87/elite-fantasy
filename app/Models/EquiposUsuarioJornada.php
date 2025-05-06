<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquiposUsuarioJornada extends Model
{
    protected $table = 'equipos_usuario_jornada';

    public $timestamps = false; // porque solo usamos created_at

    protected $fillable = [
        'user_id',
        'liga_id',
        'jornada_id',
        'jugador_id',
        'puntos',
        'posicion',
        'created_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function liga()
    {
        return $this->belongsTo(Liga::class);
    }

    public function jugador()
    {
        return $this->belongsTo(Jugador::class);
    }
}
