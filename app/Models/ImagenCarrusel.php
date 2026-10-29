<?php

namespace App\Models;

use App\Services\ProcesadorImagen;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ImagenCarrusel extends Model
{
    use HasTranslations;

    protected $table = 'imagenes_carrusel';

    public array $translatable = ['frase'];

    protected $fillable = ['imagen', 'frase', 'encuadre', 'orden', 'publicado'];

    protected $casts = [
        'publicado' => 'boolean',
    ];

    public const ENCUADRES = [
        'arriba' => ['Mostrar la parte de arriba', 'object-[50%_30%]'],
        'centro' => ['Centrado', 'object-center'],
        'abajo' => ['Mostrar la parte de abajo', 'object-[50%_65%]'],
    ];

    public function scopePublicado($query)
    {
        return $query->where('publicado', true);
    }

    public function scopeOrdenado($query)
    {
        return $query->orderBy('orden')->orderBy('id');
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/'.$this->imagen);
    }

    public function getMediumUrlAttribute(): string
    {
        return ProcesadorImagen::urlVariante($this->imagen, 'medium');
    }

    public function getThumbUrlAttribute(): string
    {
        return ProcesadorImagen::urlVariante($this->imagen, 'thumbs');
    }

    public function getClaseEncuadreAttribute(): string
    {
        return self::ENCUADRES[$this->encuadre][1] ?? 'object-center';
    }
}
