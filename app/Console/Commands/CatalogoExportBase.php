<?php

namespace App\Console\Commands;

use App\Models\Categoria;
use App\Models\Marca;
use Illuminate\Console\Command;

class CatalogoExportBase extends Command
{
    protected $signature = 'catalogo:export-base';

    protected $description = 'Exporta categorias y marcas base del catalogo a archivos JSON versionables';

    public function handle(): int
    {
        $categorias = Categoria::query()
            ->orderBy('slug')
            ->get([
                'nombre',
                'slug',
                'imagen_url',
                'mostrar_en_inicio',
                'orden_inicio',
            ])
            ->map(fn (Categoria $categoria) => [
                'nombre' => $categoria->nombre,
                'slug' => $categoria->slug,
                'imagen_url' => $categoria->imagen_url,
                'mostrar_en_inicio' => (bool) $categoria->mostrar_en_inicio,
                'orden_inicio' => $categoria->orden_inicio,
            ])
            ->values()
            ->toArray();

        $marcas = Marca::query()
            ->orderBy('nombre')
            ->get([
                'nombre',
                'mostrar_en_inicio',
                'orden_inicio',
            ])
            ->map(fn (Marca $marca) => [
                'nombre' => $marca->nombre,
                'mostrar_en_inicio' => (bool) $marca->mostrar_en_inicio,
                'orden_inicio' => $marca->orden_inicio,
            ])
            ->values()
            ->toArray();

        $this->guardarJson(database_path('data/categorias.json'), $categorias);
        $this->guardarJson(database_path('data/marcas.json'), $marcas);

        $this->info('Base de catálogo exportada correctamente.');
        $this->line(' - database/data/categorias.json');
        $this->line(' - database/data/marcas.json');

        return self::SUCCESS;
    }

    private function guardarJson(string $ruta, array $datos): void
    {
        $directorio = dirname($ruta);

        if (! is_dir($directorio)) {
            mkdir($directorio, 0755, true);
        }

        file_put_contents(
            $ruta,
            json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );
    }
}
