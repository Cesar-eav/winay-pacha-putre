<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Translatable\HasTranslations;

class Fiesta extends Model
{
    use HasTranslations;

    public array $translatable = ['titulo', 'descripcion'];

    protected $fillable = ['titulo', 'mes', 'fecha_texto', 'lugar', 'tipo', 'descripcion', 'publicado'];

    protected $casts = [
        'publicado' => 'boolean',
        'mes' => 'integer',
    ];

    public function imagenes(): MorphMany
    {
        return $this->morphMany(Imagen::class, 'imageable')->orderBy('orden');
    }

    public function scopeDelMes($query, int $mes)
    {
        return $query->where('mes', $mes);
    }

    public function scopePublicado($query)
    {
        return $query->where('publicado', true);
    }
}
