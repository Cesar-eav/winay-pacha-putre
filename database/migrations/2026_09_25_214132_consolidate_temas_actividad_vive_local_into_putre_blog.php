<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->categorias(['cultura', 'actividad', 'vive_local', 'putre_blog', 'publico_objetivo']);

        DB::table('temas')
            ->whereIn('categoria', ['actividad', 'vive_local'])
            ->update(['categoria' => 'putre_blog']);

        $this->categorias(['cultura', 'putre_blog', 'publico_objetivo']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->categorias(['cultura', 'actividad', 'vive_local', 'putre_blog', 'publico_objetivo']);

        DB::table('temas')
            ->where('categoria', 'putre_blog')
            ->update(['categoria' => 'vive_local']);

        $this->categorias(['cultura', 'actividad', 'vive_local', 'publico_objetivo']);
    }

    private function categorias(array $valores): void
    {
        Schema::table('temas', function (Blueprint $table) use ($valores) {
            $table->enum('categoria', $valores)->change();
        });
    }
};
