<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicioLocal extends Model
{
    protected $table = 'servicios_locales';

    protected $fillable = ['icono', 'nombre', 'descripcion', 'orden'];

    public function scopeOrdenado($query)
    {
        return $query->orderBy('orden');
    }
}
