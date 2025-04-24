<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Liga extends Model
{
    public $incrementing = false; // ID no autoincremental
    protected $keyType = 'string'; // Tipo del ID personalizado

    protected $fillable = [
        'id',
        'nombre',
        'password',
        'saldo_inicial', // Agregamos el campo saldo_inicial
        'administrador_id',
    ];

    // Genera un ID tipo "ABC-123" aleatorio
    public static function generateCustomId()
    {
        $letters = strtoupper(Str::random(3));
        $numbers = rand(100, 999);
        return "$letters-$numbers";
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($liga) {
            if (empty($liga->id)) {
                do {
                    $liga->id = self::generateCustomId();
                } while (self::where('id', $liga->id)->exists());
            }
        });
    }

    public function administrador()
    {
        return $this->belongsTo(User::class, 'administrador_id');
    }
}
