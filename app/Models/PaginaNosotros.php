<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Translatable\HasTranslations;

class PaginaNosotros extends Model
{
    use HasTranslations;

    protected $table = 'pagina_nosotros';

    public array $translatable = ['historia', 'mensaje'];

    protected $fillable = ['historia', 'mensaje'];

    public function imagenes(): MorphMany
    {
        return $this->morphMany(Imagen::class, 'imageable')->orderBy('orden');
    }

    public static function singleton(): self
    {
        return static::first() ?? static::create([
            'historia' => ['es' => ''],
            'mensaje' => ['es' => ''],
        ]);
    }
}
