<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LigaUser extends Model
{
    protected $table = 'liga_user';

    protected $fillable = [
        'liga_id',
        'user_id',
        'saldo',
        'puntos_totales',
    ];

    public function liga()
    {
        return $this->belongsTo(Liga::class, 'liga_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
