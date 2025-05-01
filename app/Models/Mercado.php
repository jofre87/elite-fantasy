<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mercado extends Model
{
    protected $table = 'mercado';
    protected $fillable = ['jugador_id', 'fecha'];

    public function jugador()
    {
        return $this->belongsTo(Jugador::class);
    }
}