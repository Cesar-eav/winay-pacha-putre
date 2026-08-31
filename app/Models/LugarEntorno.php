<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Translatable\HasTranslations;

class LugarEntorno extends Model
{
    use HasTranslations;

    protected $table = 'lugares_entorno';

    public array $translatable = ['descripcion'];

    protected $fillable = ['slug', 'nombre', 'icono', 'descripcion', 'ubicacion_texto', 'orden', 'publicado'];

    protected $casts = [
        'publicado' => 'boolean',
    ];

    public function imagenes(): MorphMany
    {
        return $this->morphMany(Imagen::class, 'imageable')->orderBy('orden');
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
