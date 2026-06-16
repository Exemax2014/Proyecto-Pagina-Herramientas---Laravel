<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('usuarios') || ! Schema::hasTable('domicilios')) {
            return;
        }

        $usuarios = DB::table('usuarios')
            ->select(['id', 'direccion', 'ciudad', 'provincia', 'codigo_postal'])
            ->where(function ($query) {
                $query->whereNotNull('direccion')
                    ->orWhereNotNull('ciudad')
                    ->orWhereNotNull('provincia')
                    ->orWhereNotNull('codigo_postal');
            })
            ->orderBy('id')
            ->get();

        foreach ($usuarios as $usuario) {
            $hasDomicilio = DB::table('domicilios')
                ->where('usuario_id', $usuario->id)
                ->exists();

            if ($hasDomicilio) {
                continue;
            }

            $legacy = $this->parseLegacyAddress($usuario);

            foreach (['calle', 'numero', 'ciudad', 'provincia'] as $field) {
                if ($legacy[$field] === '') {
                    continue 2;
                }
            }

            DB::table('domicilios')->insert([
                'usuario_id' => $usuario->id,
                'calle' => $legacy['calle'],
                'numero' => $legacy['numero'],
                'piso_departamento' => null,
                'ciudad' => $legacy['ciudad'],
                'provincia' => $legacy['provincia'],
                'codigo_postal' => $legacy['codigo_postal'] !== '' ? $legacy['codigo_postal'] : null,
                'referencia' => null,
                'es_principal' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // El backfill no se revierte para no borrar domicilios ya creados.
    }

    private function parseLegacyAddress(object $usuario): array
    {
        $direccion = trim((string) ($usuario->direccion ?? ''));

        if ($direccion === '') {
            return [
                'calle' => '',
                'numero' => '',
                'ciudad' => trim((string) ($usuario->ciudad ?? '')),
                'provincia' => trim((string) ($usuario->provincia ?? '')),
                'codigo_postal' => trim((string) ($usuario->codigo_postal ?? '')),
            ];
        }

        if (preg_match('/^(.*?)(?:\s+(\d+[A-Za-z0-9\-\/]*))$/', $direccion, $matches)) {
            $calle = trim($matches[1]);
            $numero = trim($matches[2]);
        } else {
            $calle = $direccion;
            $numero = '';
        }

        return [
            'calle' => $calle,
            'numero' => $numero,
            'ciudad' => trim((string) ($usuario->ciudad ?? '')),
            'provincia' => trim((string) ($usuario->provincia ?? '')),
            'codigo_postal' => trim((string) ($usuario->codigo_postal ?? '')),
        ];
    }
};
