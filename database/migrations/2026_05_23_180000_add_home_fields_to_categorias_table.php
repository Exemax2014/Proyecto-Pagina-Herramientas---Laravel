<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->string('imagen_url')->nullable()->after('slug');
            $table->boolean('mostrar_en_inicio')->default(true)->after('imagen_url');
            $table->unsignedInteger('orden_inicio')->nullable()->after('mostrar_en_inicio');
        });

        $categorias = DB::table('categorias')
            ->orderBy('id')
            ->get(['id']);

        foreach ($categorias as $index => $categoria) {
            DB::table('categorias')
                ->where('id', $categoria->id)
                ->update([
                    'mostrar_en_inicio' => true,
                    'orden_inicio' => $index + 1,
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->dropColumn(['imagen_url', 'mostrar_en_inicio', 'orden_inicio']);
        });
    }
};
