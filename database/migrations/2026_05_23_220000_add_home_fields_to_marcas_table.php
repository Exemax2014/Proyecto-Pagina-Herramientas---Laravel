<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('marcas', function (Blueprint $table) {
            $table->boolean('mostrar_en_inicio')->default(false)->after('logo_url');
            $table->unsignedInteger('orden_inicio')->nullable()->after('mostrar_en_inicio');
        });

        $marcas = DB::table('marcas')
            ->orderBy('id')
            ->get(['id']);

        foreach ($marcas as $index => $marca) {
            DB::table('marcas')
                ->where('id', $marca->id)
                ->update([
                    'mostrar_en_inicio' => $index < 9,
                    'orden_inicio' => $index < 9 ? $index + 1 : null,
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('marcas', function (Blueprint $table) {
            $table->dropColumn(['mostrar_en_inicio', 'orden_inicio']);
        });
    }
};
