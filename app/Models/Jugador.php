<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jugador extends Model
{
    use HasFactory;

    protected $table = 'jugadores';
    protected $fillable = [
        'id', // Asegúrate de permitir el ID personalizado
        'nombre',
        'posicion',
        'equipo_id',
        'imagen',
        'valor_actual',
        'diferencia',
    ];

    public $incrementing = false;
    protected $keyType = 'int';

    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'equipo_id');
    }

    public function estadisticasTemporada()
    {
        return $this->hasOne(EstadisticaTemporada::class);
    }
}
