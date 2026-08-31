<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Translatable\HasTranslations;

class Cabana extends Model
{
    use HasTranslations;

    public array $translatable = ['descripcion'];

    protected $fillable = ['slug', 'nombre', 'capacidad', 'descripcion', 'precio_desde', 'orden', 'publicado'];

    protected $casts = [
        'publicado' => 'boolean',
        'capacidad' => 'integer',
    ];

    public function imagenes(): MorphMany
    {
        return $this->morphMany(Imagen::class, 'imageable')->orderBy('orden');
    }

    public function equipamientos(): BelongsToMany
    {
        return $this->belongsToMany(Equipamiento::class, 'cabana_equipamiento');
    }

    public function solicitudesReserva(): HasMany
    {
        return $this->hasMany(SolicitudReserva::class);
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
