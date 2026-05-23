<?php

namespace Database\Seeders;

use App\Models\Marca;
use Illuminate\Database\Seeder;
use RuntimeException;

class MarcaSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('data/marcas.json');

        if (! file_exists($jsonPath)) {
            throw new RuntimeException("No se encontró el archivo marcas.json en: {$jsonPath}");
        }

        $marcas = json_decode(file_get_contents($jsonPath), true);

        if (! is_array($marcas)) {
            throw new RuntimeException('El archivo marcas.json no tiene un formato JSON válido.');
        }

        foreach ($marcas as $data) {
            Marca::updateOrCreate(
                ['nombre' => $data['nombre']],
                [
                    'mostrar_en_inicio' => $data['mostrar_en_inicio'] ?? false,
                    'orden_inicio' => $data['orden_inicio'] ?? null,
                ]
            );
        }
    }
}
