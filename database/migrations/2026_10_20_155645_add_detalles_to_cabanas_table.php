<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cabanas', function (Blueprint $table) {
            $table->unsignedSmallInteger('numero')->nullable()->unique()->after('id');
            $table->unsignedTinyInteger('capacidad')->nullable()->change();
            $table->unsignedTinyInteger('cantidad_banos')->default(1)->after('capacidad');
            $table->boolean('bano_privado')->default(true)->after('cantidad_banos');
            $table->string('imagen_principal')->nullable()->after('precio_desde');
        });
    }

    public function down(): void
    {
        Schema::table('cabanas', function (Blueprint $table) {
            $table->dropUnique(['numero']);
            $table->dropColumn(['numero', 'cantidad_banos', 'bano_privado', 'imagen_principal']);
            $table->unsignedTinyInteger('capacidad')->nullable(false)->change();
        });
    }
};
