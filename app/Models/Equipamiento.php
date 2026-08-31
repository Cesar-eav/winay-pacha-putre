<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Equipamiento extends Model
{
    protected $fillable = ['nombre', 'icono', 'ambito', 'orden'];

    public function cabanas(): BelongsToMany
    {
        return $this->belongsToMany(Cabana::class, 'cabana_equipamiento');
    }

    public function scopeAmbito($query, string $ambito)
    {
        return $query->where('ambito', $ambito);
    }
}
