<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE temas MODIFY categoria ENUM('cultura', 'actividad', 'vive_local', 'putre_blog', 'publico_objetivo') NOT NULL");

        DB::table('temas')
            ->whereIn('categoria', ['actividad', 'vive_local'])
            ->update(['categoria' => 'putre_blog']);

        DB::statement("ALTER TABLE temas MODIFY categoria ENUM('cultura', 'putre_blog', 'publico_objetivo') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE temas MODIFY categoria ENUM('cultura', 'actividad', 'vive_local', 'putre_blog', 'publico_objetivo') NOT NULL");

        DB::table('temas')
            ->where('categoria', 'putre_blog')
            ->update(['categoria' => 'vive_local']);

        DB::statement("ALTER TABLE temas MODIFY categoria ENUM('cultura', 'actividad', 'vive_local', 'publico_objetivo') NOT NULL");
    }
};
