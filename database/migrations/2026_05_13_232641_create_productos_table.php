<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void{
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 10, 2);
            $table->decimal('precio_anterior', 10, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->integer('ventas')->default(0);
            $table->enum('energia', ['electrica', 'manual', 'inalambrica']);
            $table->string('etiqueta', 50)->nullable();
            $table->string('etiqueta_clase', 100)->nullable();
            $table->boolean('activo')->default(true);
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->foreignId('marca_id')->constrained('marcas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
