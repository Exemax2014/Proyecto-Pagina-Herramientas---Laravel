<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consultas', function (Blueprint $table) {
            $table->foreignId('usuario_id')
                ->nullable()
                ->after('id')
                ->constrained('usuarios')
                ->nullOnDelete();

            $table->string('telefono', 20)
                ->nullable()
                ->after('correo');
        });
    }

    public function down(): void
    {
        Schema::table('consultas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('usuario_id');
            $table->dropColumn('telefono');
        });
    }
};
