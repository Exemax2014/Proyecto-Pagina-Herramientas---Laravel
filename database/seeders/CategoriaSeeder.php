<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;
use RuntimeException;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('data/categorias.json');

        if (! file_exists($jsonPath)) {
            throw new RuntimeException("No se encontró el archivo categorias.json en: {$jsonPath}");
        }

        $categorias = json_decode(file_get_contents($jsonPath), true);

        if (! is_array($categorias)) {
            throw new RuntimeException('El archivo categorias.json no tiene un formato JSON válido.');
        }

        foreach ($categorias as $data) {
            Categoria::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'nombre' => $data['nombre'],
                    'imagen_url' => $data['imagen_url'] ?? null,
                    'mostrar_en_inicio' => $data['mostrar_en_inicio'] ?? false,
                    'orden_inicio' => $data['orden_inicio'] ?? null,
                ]
            );
        }
    }
}
