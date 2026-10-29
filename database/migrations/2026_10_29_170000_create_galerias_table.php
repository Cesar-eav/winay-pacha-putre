<?php

use App\Models\Galeria;
use App\Services\ProcesadorImagen;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Las fotos de "Quienes ya vivieron Wiñaypacha" estaban escritas en la vista
 * del inicio. Se importan (public/images/inicio/turistas) a una galería
 * editable desde el admin.
 */
return new class extends Migration
{
    private const FOTOS_ACTUALES = [
        ['turista-1-960.webp', 'Huéspedes en Wiñaypacha Putre'],
        ['turista-2-960.webp', 'Huéspedes en Wiñaypacha Putre'],
        ['turista-3-960.webp', 'Huéspedes en Wiñaypacha Putre'],
        ['turista-4-960.webp', 'Huéspedes en Wiñaypacha Putre'],
        ['turista-5-960.webp', 'Huéspedes fotografiando el valle de Putre'],
        ['turista-6-960.webp', 'Huéspedes en Wiñaypacha Putre'],
        ['turista-7-960.webp', 'Huéspedes en Wiñaypacha Putre'],
        ['turista-8-480.webp', 'Huéspedes en Wiñaypacha Putre'],
        ['turista-9-480.webp', 'Huéspedes en Wiñaypacha Putre'],
    ];

    public function up(): void
    {
        Schema::create('galerias', function (Blueprint $table) {
            $table->id();
            $table->string('clave')->unique();
            $table->timestamps();
        });

        $galeriaId = DB::table('galerias')->insertGetId([
            'clave' => Galeria::HUESPEDES,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // RefreshDatabase corre las migraciones antes de Storage::fake(): importar escribiría en el storage real.
        if (app()->runningUnitTests()) {
            return;
        }

        foreach (self::FOTOS_ACTUALES as $orden => [$archivo, $alt]) {
            $origen = public_path('images/inicio/turistas/'.$archivo);

            if (! is_file($origen)) {
                continue;
            }

            DB::table('imagenes')->insert([
                'imageable_type' => Galeria::class,
                'imageable_id' => $galeriaId,
                'path' => ProcesadorImagen::guardar(file_get_contents($origen), 'huespedes'),
                'alt' => $alt,
                'orden' => $orden,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        $imagenes = DB::table('imagenes')->where('imageable_type', Galeria::class);

        foreach ($imagenes->pluck('path') as $path) {
            ProcesadorImagen::eliminar($path);
        }

        $imagenes->delete();

        Schema::dropIfExists('galerias');
    }
};
