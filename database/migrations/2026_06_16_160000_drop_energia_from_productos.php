<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DropEnergiaFromProductos extends Migration
{
    public function up()
    {
        if (! Schema::hasColumn('productos', 'energia')) {
            return;
        }

        // Preservar valores existentes en el campo 'descripcion' evitando duplicados
        $productos = DB::table('productos')
            ->select('id', 'energia', 'descripcion')
            ->whereNotNull('energia')
            ->get();

        $map = [
            'electrica' => 'Eléctrica',
            'manual' => 'Manual',
            'inalambrica' => 'Inalámbrica',
            'bateria' => 'Batería',
        ];

        foreach ($productos as $p) {
            $energia = trim((string) ($p->energia ?? ''));

            if ($energia === '') {
                continue;
            }

            $energiaText = $map[strtolower($energia)] ?? ucfirst($energia);
            $nota = 'Tipo de energía: ' . $energiaText;

            $descripcion = (string) ($p->descripcion ?? '');

            // Evitar duplicar si ya contiene la marca
            if ($descripcion === '' || stripos($descripcion, 'Tipo de energía') === false) {
                $nuevo = $descripcion === '' ? $nota : trim($descripcion) . "\n\n" . $nota;
                DB::table('productos')->where('id', $p->id)->update(['descripcion' => $nuevo]);
            }
        }

        Schema::table('productos', function (Blueprint $table) {
            // Drop column only if exists (we already checked)
            $table->dropColumn('energia');
        });
    }

    public function down()
    {
        if (! Schema::hasColumn('productos', 'energia')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->string('energia')->nullable();
            });
        }
    }
}
