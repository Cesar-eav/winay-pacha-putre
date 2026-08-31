<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cabana_equipamiento', function (Blueprint $table) {
            $table->foreignId('cabana_id')->constrained()->cascadeOnDelete();
            $table->foreignId('equipamiento_id')->constrained()->cascadeOnDelete();
            $table->primary(['cabana_id', 'equipamiento_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cabana_equipamiento');
    }
};
