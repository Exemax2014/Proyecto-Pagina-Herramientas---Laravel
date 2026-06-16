<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn(['direccion', 'ciudad', 'provincia', 'codigo_postal']);
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->string('direccion')->nullable()->after('telefono');
            $table->string('ciudad', 100)->nullable()->after('direccion');
            $table->string('provincia', 100)->nullable()->after('ciudad');
            $table->string('codigo_postal', 20)->nullable()->after('provincia');
        });
    }
};
