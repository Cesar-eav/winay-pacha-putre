<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Translatable\HasTranslations;

class PaginaNosotros extends Model
{
    use HasTranslations;

    protected $table = 'pagina_nosotros';

    public array $translatable = ['titulo_historia', 'historia', 'titulo_mensaje', 'mensaje'];

    protected $fillable = ['titulo_historia', 'historia', 'titulo_mensaje', 'mensaje'];

    public function imagenes(): MorphMany
    {
        return $this->morphMany(Imagen::class, 'imageable')->orderBy('orden');
    }

    public static function singleton(): self
    {
        return static::first() ?? static::create([
            'titulo_historia' => ['es' => 'Nuestra historia'],
            'historia' => ['es' => ''],
            'titulo_mensaje' => ['es' => 'Nuestro mensaje'],
            'mensaje' => ['es' => ''],
        ]);
    }
}
