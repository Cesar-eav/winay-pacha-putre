<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolicitudReserva extends Model
{
    protected $table = 'solicitudes_reserva';

    protected $fillable = [
        'nombre', 'apellido', 'correo', 'whatsapp',
        'fecha_llegada', 'fecha_salida', 'num_personas',
        'cabana_id', 'comentarios', 'estado',
    ];

    protected $casts = [
        'fecha_llegada' => 'date',
        'fecha_salida' => 'date',
    ];

    public function cabana(): BelongsTo
    {
        return $this->belongsTo(Cabana::class);
    }
}
