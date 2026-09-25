<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pagina_nosotros', function (Blueprint $table) {
            $table->json('titulo_historia')->after('id');
            $table->json('titulo_mensaje')->after('historia');
        });

        DB::table('pagina_nosotros')->update([
            'titulo_historia' => json_encode(['es' => 'Nuestra historia']),
            'titulo_mensaje' => json_encode(['es' => 'Nuestro mensaje']),
        ]);
    }

    public function down(): void
    {
        Schema::table('pagina_nosotros', function (Blueprint $table) {
            $table->dropColumn(['titulo_historia', 'titulo_mensaje']);
        });
    }
};
