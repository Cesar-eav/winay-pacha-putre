<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Galerías fijas del sitio que no pertenecen a otro contenido (p. ej. las
 * fotos de huéspedes del inicio). Cada una se identifica por su clave.
 */
class Galeria extends Model
{
    public const HUESPEDES = 'huespedes';

    protected $fillable = ['clave'];

    public static function de(string $clave): self
    {
        return static::firstOrCreate(['clave' => $clave]);
    }

    public function imagenes(): MorphMany
    {
        return $this->morphMany(Imagen::class, 'imageable')->orderBy('orden');
    }
}
