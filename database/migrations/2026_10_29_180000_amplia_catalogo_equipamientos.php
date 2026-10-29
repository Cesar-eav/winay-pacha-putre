<?php

use App\Support\CatalogoEquipamiento;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Nombre ES de las filas creadas antes del catálogo => [clave, EN, FR]. Las traducciones vivían en
     * lang/*.json, de donde se sacaron en este mismo cambio.
     */
    private const EXISTENTES = [
        'Cocina equipada' => ['cocina-equipada', 'Equipped kitchen', 'Cuisine équipée'],
        'Estufa a leña' => ['estufa-lena', 'Wood-burning stove', 'Poêle à bois'],
        'Agua caliente' => ['agua-caliente', 'Hot water', 'Eau chaude'],
        'Wifi' => ['wifi', 'Wi-Fi', 'Wi-Fi'],
        'Estacionamiento' => ['estacionamiento', 'Parking', 'Parking'],
        'Cama matrimonial' => ['cama-matrimonial', 'Double bed', 'Lit double'],
        'Ropa de cama' => ['ropa-cama', 'Bed linen', 'Linge de lit'],
        'Calefacción' => ['calefactor-electrico', 'Heating', 'Chauffage'],
        'Calefactor eléctrico' => ['calefactor-electrico', 'Electric heater', 'Radiateur électrique'],
        'Velador' => ['velador', 'Nightstand', 'Table de chevet'],
        'Comedor' => ['comedor', 'Dining area', 'Coin repas'],
        'Frigobar' => ['frigobar', 'Mini fridge', 'Mini-réfrigérateur'],
        'Microondas' => ['microondas', 'Microwave', 'Micro-ondes'],
        'Hervidor' => ['hervidor', 'Electric kettle', 'Bouilloire électrique'],
        'TV 34"' => ['tv', '34" TV', 'Télévision 34"'],
        'Lavaplatos' => ['lavaplatos', 'Kitchen sink', 'Évier'],
        'Sofá' => ['sofa', 'Sofa', 'Canapé'],
        'Mueble para ropa' => ['mueble-ropa', 'Clothes storage', 'Rangement pour vêtements'],
        'Ropero' => ['ropero', 'Wardrobe', 'Armoire'],
    ];

    public function up(): void
    {
        Schema::table('equipamientos', function (Blueprint $table) {
            $table->string('clave')->nullable()->unique()->after('id');
            $table->string('categoria')->default('otros')->after('icono');
            $table->json('nombre_traducido')->nullable()->after('nombre');
        });

        $usadas = [];

        foreach (DB::table('equipamientos')->get() as $fila) {
            [$clave, $en, $fr] = self::EXISTENTES[$fila->nombre] ?? [null, null, null];

            DB::table('equipamientos')->where('id', $fila->id)->update([
                'clave' => $clave && ! isset($usadas[$clave]) ? $clave : null,
                'nombre_traducido' => json_encode(array_filter(['es' => $fila->nombre, 'en' => $en, 'fr' => $fr]), JSON_UNESCAPED_UNICODE),
            ]);

            if ($clave) {
                $usadas[$clave] = true;
            }
        }

        Schema::table('equipamientos', function (Blueprint $table) {
            $table->dropColumn(['nombre', 'ambito']);
        });

        Schema::table('equipamientos', function (Blueprint $table) {
            $table->renameColumn('nombre_traducido', 'nombre');
        });

        CatalogoEquipamiento::sincronizar();
    }

    public function down(): void
    {
        Schema::table('equipamientos', function (Blueprint $table) {
            $table->string('nombre_es')->default('')->after('id');
            $table->enum('ambito', ['cabana', 'habitacion'])->default('cabana');
        });

        foreach (DB::table('equipamientos')->get() as $fila) {
            DB::table('equipamientos')->where('id', $fila->id)->update([
                'nombre_es' => json_decode($fila->nombre, true)['es'] ?? '',
                'ambito' => in_array($fila->categoria, ['dormitorio', 'bano']) ? 'habitacion' : 'cabana',
            ]);
        }

        Schema::table('equipamientos', function (Blueprint $table) {
            $table->dropUnique(['clave']);
            $table->dropColumn(['nombre', 'clave', 'categoria']);
        });

        Schema::table('equipamientos', function (Blueprint $table) {
            $table->renameColumn('nombre_es', 'nombre');
        });
    }
};
