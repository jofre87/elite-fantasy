<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RachaPuntos extends Model
{
    use HasFactory;

    protected $fillable = ['jugador_id', 'partido', 'puntos', 'categoria'];

    public function jugador()
    {
        return $this->belongsTo(Jugador::class);
    }
}
