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
        Schema::create('etiquetas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('slug', 120)->unique();
            $table->string('color', 20)->default('#111111');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        $timestamp = now();

        DB::table('etiquetas')->insert([
            'nombre' => 'Oferta',
            'slug' => 'oferta',
            'color' => '#d9a441',
            'activo' => true,
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ]);

        if (! Schema::hasTable('productos') || ! Schema::hasColumn('productos', 'etiqueta')) {
            return;
        }

        $etiquetasExistentes = DB::table('productos')
            ->select('etiqueta', 'etiqueta_clase')
            ->whereNotNull('etiqueta')
            ->where('etiqueta', '!=', '')
            ->distinct()
            ->get();

        foreach ($etiquetasExistentes as $etiqueta) {
            $nombre = trim((string) $etiqueta->etiqueta);

            if ($nombre === '') {
                continue;
            }

            $slug = Str::slug($nombre);
            $slug = $slug !== '' ? $slug : Str::lower($nombre);

            DB::table('etiquetas')->updateOrInsert(
                ['slug' => $slug],
                [
                    'nombre' => $nombre,
                    'color' => match ($etiqueta->etiqueta_clase) {
                        'product-card-badge-dark' => '#111111',
                        'product-card-badge-soft' => '#f4efe7',
                        default => $nombre === 'Oferta' ? '#d9a441' : '#111111',
                    },
                    'activo' => true,
                    'updated_at' => $timestamp,
                    'created_at' => $timestamp,
                ]
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('etiquetas');
    }
};
