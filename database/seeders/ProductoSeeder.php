<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\ProductoImagen;
use RuntimeException;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('data/productos.json');

        if (! file_exists($jsonPath)) {
            throw new RuntimeException("No se encontró el archivo productos.json en: {$jsonPath}");
        }

        $productos = json_decode(file_get_contents($jsonPath), true);

        if (! is_array($productos)) {
            throw new RuntimeException('El archivo productos.json no tiene un formato JSON válido.');
        }

        foreach ($productos as $data) {
            /*
            |--------------------------------------------------------------------------
            | Categoría
            |--------------------------------------------------------------------------
            */
            $categoria = Categoria::updateOrCreate(
                ['slug' => $data['categoria']['slug']],
                [
                    'nombre' => $data['categoria']['nombre'],
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | Marca
            |--------------------------------------------------------------------------
            */
            $marca = Marca::updateOrCreate(
                ['nombre' => $data['marca']['nombre']],
                [
                    'logo_url' => $data['marca']['logo_url'] ?? null,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | Producto
            |--------------------------------------------------------------------------
            */
            $producto = Producto::updateOrCreate(
                ['nombre' => $data['nombre']],
                [
                    'descripcion'     => $data['descripcion'],
                    'precio'          => $data['precio'],
                    'precio_anterior' => $data['precio_anterior'] ?? null,
                    'stock'           => $data['stock'] ?? 10,
                    'ventas'          => $data['ventas'] ?? 0,
                    'energia'         => $data['energia'],
                    'etiqueta'        => $data['etiqueta'] ?? null,
                    'etiqueta_clase'  => $data['etiqueta_clase'] ?? null,
                    'activo'          => $data['activo'] ?? true,
                    'categoria_id'    => $categoria->id,
                    'marca_id'        => $marca->id,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | Imágenes del producto
            |--------------------------------------------------------------------------
            */
            if (! empty($data['imagenes']) && is_array($data['imagenes'])) {
                foreach ($data['imagenes'] as $imagen) {
                    ProductoImagen::updateOrCreate(
                        [
                            'producto_id' => $producto->id,
                            'url'         => $imagen['url'],
                        ],
                        [
                            'orden'        => $imagen['orden'] ?? 0,
                            'es_principal' => $imagen['es_principal'] ?? false,
                        ]
                    );
                }
            }
        }
    }
}