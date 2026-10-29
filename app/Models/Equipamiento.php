<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class Equipamiento extends Model
{
    use HasTranslations;

    protected $fillable = ['clave', 'nombre', 'icono', 'categoria', 'orden'];

    public array $translatable = ['nombre'];

    public function cabanas(): BelongsToMany
    {
        return $this->belongsToMany(Cabana::class, 'cabana_equipamiento');
    }

    public function getPersonalizadoAttribute(): bool
    {
        return $this->clave === null;
    }
}
