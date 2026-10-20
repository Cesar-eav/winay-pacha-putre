<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * La imagen principal de una cabaña pasa a ser una de las fotos de su
 * galería. Las que se subieron por separado se agregan como primera foto.
 */
return new class extends Migration
{
    public function up(): void
    {
        $tipo = 'App\Models\Cabana';

        foreach (DB::table('cabanas')->whereNotNull('imagen_principal')->get() as $cabana) {
            $galeria = DB::table('imagenes')->where('imageable_type', $tipo)->where('imageable_id', $cabana->id);

            if ((clone $galeria)->where('path', $cabana->imagen_principal)->exists()) {
                continue;
            }

            (clone $galeria)->increment('orden');

            DB::table('imagenes')->insert([
                'imageable_type' => $tipo,
                'imageable_id' => $cabana->id,
                'path' => $cabana->imagen_principal,
                'alt' => $cabana->nombre,
                'orden' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
    }
};
