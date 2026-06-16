<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('domicilios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')
                ->constrained('usuarios')
                ->cascadeOnDelete();
            $table->string('calle');
            $table->string('numero');
            $table->string('piso_departamento')->nullable();
            $table->string('ciudad');
            $table->string('provincia');
            $table->string('codigo_postal')->nullable();
            $table->string('referencia')->nullable();
            $table->boolean('es_principal')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('domicilios');
    }
};
