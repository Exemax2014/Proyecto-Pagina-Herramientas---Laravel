<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->foreignId('etiqueta_id')
                ->nullable()
                ->after('etiqueta_clase')
                ->constrained('etiquetas')
                ->nullOnDelete();
        });

        if (! Schema::hasColumn('productos', 'etiqueta')) {
            return;
        }

        $etiquetasBySlug = DB::table('etiquetas')
            ->get()
            ->keyBy(fn ($etiqueta) => $etiqueta->slug);

        DB::table('productos')
            ->select('id', 'etiqueta')
            ->whereNotNull('etiqueta')
            ->where('etiqueta', '!=', '')
            ->orderBy('id')
            ->chunkById(100, function ($productos) use ($etiquetasBySlug) {
                foreach ($productos as $producto) {
                    $slug = Str::slug((string) $producto->etiqueta);
                    $slug = $slug !== '' ? $slug : Str::lower(trim((string) $producto->etiqueta));
                    $etiqueta = $etiquetasBySlug->get($slug);

                    if (! $etiqueta) {
                        continue;
                    }

                    DB::table('productos')
                        ->where('id', $producto->id)
                        ->update(['etiqueta_id' => $etiqueta->id]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('etiqueta_id');
        });
    }
};
