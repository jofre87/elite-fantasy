<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadisticaPartidosRecientes extends Model
{
    use HasFactory;
    protected $table = 'estadisticas_partidos_recientes';
    protected $fillable = ['jugador_id', 'rango', 'puntos', 'partidos_jugados', 'media'];

    public function jugador()
    {
        return $this->belongsTo(Jugador::class);
    }
}
