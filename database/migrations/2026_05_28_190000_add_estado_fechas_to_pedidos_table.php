<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->timestamp('fecha_preparando')->nullable()->after('fecha_confirmacion');
            $table->timestamp('fecha_enviado')->nullable()->after('fecha_preparando');
            $table->timestamp('fecha_entregado')->nullable()->after('fecha_enviado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn([
                'fecha_preparando',
                'fecha_enviado',
                'fecha_entregado',
            ]);
        });
    }
};
