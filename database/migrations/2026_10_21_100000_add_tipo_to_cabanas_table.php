<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cabanas', function (Blueprint $table) {
            $table->string('tipo', 20)->default('cabana')->after('id')->index();
            $table->dropUnique(['numero']);
            $table->unique(['tipo', 'numero']);
        });
    }

    public function down(): void
    {
        Schema::table('cabanas', function (Blueprint $table) {
            $table->dropUnique(['tipo', 'numero']);
            $table->unique('numero');
            $table->dropIndex(['tipo']);
            $table->dropColumn('tipo');
        });
    }
};
