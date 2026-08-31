<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Translatable\HasTranslations;

class Tema extends Model
{
    use HasTranslations;

    public array $translatable = ['titulo', 'cuerpo'];

    protected $fillable = ['slug', 'categoria', 'titulo', 'cuerpo', 'orden', 'publicado'];

    protected $casts = [
        'publicado' => 'boolean',
    ];

    public function imagenes(): MorphMany
    {
        return $this->morphMany(Imagen::class, 'imageable')->orderBy('orden');
    }

    public function scopeCategoria($query, string $categoria)
    {
        return $query->where('categoria', $categoria);
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
