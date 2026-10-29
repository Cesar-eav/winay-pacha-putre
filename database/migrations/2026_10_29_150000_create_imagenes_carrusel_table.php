<?php

use App\Services\ProcesadorImagen;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * El carrusel del inicio estaba escrito en la vista. Se importan sus fotos
 * (public/images/inicio/banner) para que queden editables desde el admin,
 * con las frases EN/FR que ya estaban traducidas en lang/*.json.
 */
return new class extends Migration
{
    private const FOTOS_ACTUALES = [
        ['archivo' => 'cabanas-cerros-2000.webp', 'frase' => 'Cabañas Wiñaypacha Putre al pie de los cerros'],
        ['archivo' => 'laguna-flamenco-1536.webp', 'frase' => 'Flamenco en una laguna del altiplano'],
        ['archivo' => 'cabanas-2000.webp', 'frase' => 'Cabañas Wiñaypacha Putre'],
        ['archivo' => 'recinto-2000.webp', 'frase' => null],
    ];

    public function up(): void
    {
        Schema::create('imagenes_carrusel', function (Blueprint $table) {
            $table->id();
            $table->string('imagen');
            $table->json('frase')->nullable();
            $table->string('encuadre')->default('centro');
            $table->unsignedSmallInteger('orden')->default(0);
            $table->boolean('publicado')->default(true);
            $table->timestamps();
        });

        // RefreshDatabase corre las migraciones antes de Storage::fake(): importar escribiría en el storage real.
        if (app()->runningUnitTests()) {
            return;
        }

        foreach (self::FOTOS_ACTUALES as $orden => $foto) {
            $origen = public_path('images/inicio/banner/'.$foto['archivo']);

            if (! is_file($origen)) {
                continue;
            }

            $frase = $foto['frase'] ? [
                'es' => $foto['frase'],
                'en' => __($foto['frase'], [], 'en'),
                'fr' => __($foto['frase'], [], 'fr'),
            ] : null;

            DB::table('imagenes_carrusel')->insert([
                'imagen' => ProcesadorImagen::guardar(file_get_contents($origen), 'carrusel'),
                'frase' => $frase ? json_encode($frase, JSON_UNESCAPED_UNICODE) : null,
                'encuadre' => 'abajo',
                'orden' => $orden,
                'publicado' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        foreach (DB::table('imagenes_carrusel')->pluck('imagen') as $imagen) {
            ProcesadorImagen::eliminar($imagen);
        }

        Schema::dropIfExists('imagenes_carrusel');
    }
};
