<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jugador extends Model
{
    use HasFactory;

    protected $table = 'jugadores';
    protected $fillable = ['id', 'nombre', 'posicion', 'equipo_id', 'imagen', 'ratio'];

    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'equipo_id');
    }

    public function estadisticasTemporada()
    {
        return $this->hasOne(EstadisticaTemporada::class);
    }

    public function estadisticasPartidosRecientes()
    {
        return $this->hasMany(EstadisticaPartidosRecientes::class);
    }

    public function rachaPuntos()
    {
        return $this->hasMany(RachaPuntos::class);
    }
}
