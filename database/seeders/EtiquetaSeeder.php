<?php

namespace Database\Seeders;

use App\Models\Etiqueta;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EtiquetaSeeder extends Seeder
{
    public function run(): void
    {
        $etiquetas = [
            ['nombre' => 'Oferta', 'color' => '#d9a441'],
            ['nombre' => 'Nuevo', 'color' => '#f4efe7'],
            ['nombre' => 'Destacado', 'color' => '#111111'],
            ['nombre' => 'Mas vendido', 'color' => '#d86c3d'],
        ];

        foreach ($etiquetas as $etiqueta) {
            Etiqueta::updateOrCreate(
                ['slug' => Str::slug($etiqueta['nombre'])],
                [
                    'nombre' => $etiqueta['nombre'],
                    'color' => $etiqueta['color'],
                    'activo' => true,
                ]
            );
        }
    }
}
