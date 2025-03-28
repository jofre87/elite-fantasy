<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadisticaTemporada extends Model
{
    use HasFactory;

    protected $table = 'estadisticas_temporada';
    protected $fillable = ['jugador_id', 'puntos_totales', 'media_puntos', 'partidos_jugados'];

    public function jugador()
    {
        return $this->belongsTo(Jugador::class);
    }
}
