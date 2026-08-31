<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitudes_reserva', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('correo');
            $table->string('whatsapp')->nullable();
            $table->date('fecha_llegada');
            $table->date('fecha_salida');
            $table->unsignedTinyInteger('num_personas');
            $table->foreignId('cabana_id')->nullable()->constrained('cabanas')->nullOnDelete();
            $table->text('comentarios')->nullable();
            $table->enum('estado', ['nuevo', 'contactado', 'confirmado', 'cerrado'])->default('nuevo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes_reserva');
    }
};
