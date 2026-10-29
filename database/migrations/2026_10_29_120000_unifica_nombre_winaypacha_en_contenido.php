<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * "Wiñay Pacha" / "WiñayPacha" pasa a escribirse siempre junto: "Wiñaypacha".
 * Los campos traducibles son JSON y pueden tener la ñ escapada como ñ.
 */
return new class extends Migration
{
    public function up(): void
    {
        $columnas = [
            'temas' => ['titulo', 'cuerpo'],
            'fiestas' => ['titulo', 'descripcion', 'fecha_texto'],
            'especies' => ['descripcion', 'donde_observar'],
            'lugares_entorno' => ['nombre', 'descripcion'],
            'cabanas' => ['nombre', 'descripcion'],
            'configuraciones' => ['valor'],
        ];

        $reemplazos = [
            'Wiñay Pacha' => 'Wiñaypacha',
            'WiñayPacha' => 'Wiñaypacha',
            'Wi\\u00f1ay Pacha' => 'Wi\\u00f1aypacha',
            'Wi\\u00f1ayPacha' => 'Wi\\u00f1aypacha',
        ];

        foreach ($columnas as $tabla => $campos) {
            foreach ($campos as $campo) {
                foreach ($reemplazos as $de => $a) {
                    DB::table($tabla)->update([$campo => DB::raw('REPLACE(`'.$campo.'`, '.DB::getPdo()->quote($de).', '.DB::getPdo()->quote($a).')')]);
                }
            }
        }
    }

    public function down(): void
    {
    }
};
