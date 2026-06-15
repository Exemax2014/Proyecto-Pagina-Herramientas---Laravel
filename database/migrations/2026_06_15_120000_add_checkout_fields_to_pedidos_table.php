<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->string('dni', 20)->nullable()->after('codigo_postal');
            $table->string('modo_entrega', 50)->nullable()->after('metodo_pago');
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn(['dni', 'modo_entrega']);
        });
    }
};
