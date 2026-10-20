<?php

namespace App\Models;

use App\Services\ProcesadorImagen;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Translatable\HasTranslations;

class Cabana extends Model
{
    use HasTranslations;

    public array $translatable = ['descripcion'];

    protected $fillable = [
        'numero', 'slug', 'nombre', 'capacidad', 'cantidad_banos', 'bano_privado',
        'descripcion', 'precio_desde', 'imagen_principal', 'orden', 'publicado',
    ];

    protected $casts = [
        'publicado' => 'boolean',
        'bano_privado' => 'boolean',
        'numero' => 'integer',
        'capacidad' => 'integer',
        'cantidad_banos' => 'integer',
    ];

    public function getImagenPrincipalUrlAttribute(): ?string
    {
        return $this->imagen_principal ? ProcesadorImagen::urlVariante($this->imagen_principal, 'medium') : null;
    }

    public function getImagenPrincipalThumbUrlAttribute(): ?string
    {
        return $this->imagen_principal ? ProcesadorImagen::urlVariante($this->imagen_principal, 'thumbs') : null;
    }

    public function getPortadaUrlAttribute(): ?string
    {
        return $this->imagen_principal_url ?? $this->imagenes->first()?->medium_url;
    }

    public function getPortadaAltAttribute(): string
    {
        $portada = $this->imagen_principal
            ? $this->imagenes->firstWhere('path', $this->imagen_principal)
            : $this->imagenes->first();

        return $portada?->alt ?: $this->nombre;
    }

    public function galeriaPublica(): Collection
    {
        if (! $this->imagen_principal) {
            return $this->imagenes;
        }

        return new Collection([
            $this->imagenes->firstWhere('path', $this->imagen_principal)
                ?? new Imagen(['path' => $this->imagen_principal, 'alt' => $this->nombre]),
            ...$this->imagenes->reject(fn ($imagen) => $imagen->path === $this->imagen_principal)->all(),
        ]);
    }

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
