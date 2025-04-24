<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JugadorUserLiga extends Model
{
    protected $table = 'jugador_user_liga';

    protected $fillable = [
        'jugador_id',
        'user_id',
        'liga_id',
        'comprado_en',
    ];

    public function jugador()
    {
        return $this->belongsTo(Jugador::class, 'jugador_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function liga()
    {
        return $this->belongsTo(Liga::class, 'liga_id');
    }
}
