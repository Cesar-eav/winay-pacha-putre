<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Especie extends Model
{
    use HasTranslations;

    public array $translatable = ['descripcion', 'donde_observar'];

    protected $fillable = [
        'nombre_comun', 'nombre_cientifico', 'tipo', 'descripcion',
        'donde_observar', 'imagen', 'orden', 'publicado',
    ];

    protected $casts = [
        'publicado' => 'boolean',
    ];

    public function scopeTipo($query, string $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopePublicado($query)
    {
        return $query->where('publicado', true);
    }

    public function scopeOrdenado($query)
    {
        return $query->orderBy('orden');
    }
}
