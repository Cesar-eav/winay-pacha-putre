<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fiestas', function (Blueprint $table) {
            $table->id();
            $table->json('titulo');
            $table->unsignedTinyInteger('mes');
            $table->string('fecha_texto')->nullable();
            $table->string('lugar')->nullable();
            $table->string('tipo')->nullable();
            $table->json('descripcion');
            $table->boolean('publicado')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fiestas');
    }
};
