<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class AdminPedidoController extends Controller
{
    private const ESTADOS_PERMITIDOS = [
        'confirmado',
        'preparando',
        'enviado',
        'entregado',
    ];

    public function index(Request $request): View
    {
        $buscar = trim((string) $request->input('buscar', ''));
        $estado = (string) $request->input('estado', '');

        $baseQuery = Pedido::query()
            ->where('estado', '!=', 'carrito');

        $resumen = (clone $baseQuery)
            ->selectRaw('COUNT(*) as total_pedidos')
            ->selectRaw("SUM(CASE WHEN estado = 'confirmado' THEN 1 ELSE 0 END) as confirmados")
            ->selectRaw("SUM(CASE WHEN estado = 'preparando' THEN 1 ELSE 0 END) as preparando")
            ->selectRaw("SUM(CASE WHEN estado = 'enviado' THEN 1 ELSE 0 END) as enviados")
            ->selectRaw("SUM(CASE WHEN estado = 'entregado' THEN 1 ELSE 0 END) as entregados")
            ->selectRaw('COALESCE(SUM(total), 0) as total_facturado')
            ->first();

        $pedidos = (clone $baseQuery)
            ->with(['usuario'])
            ->when($estado !== '', function (Builder $query) use ($estado) {
                $query->where('estado', $estado);
            })
            ->when($buscar !== '', function (Builder $query) use ($buscar) {
                $query->where(function (Builder $subQuery) use ($buscar) {
                    if (ctype_digit($buscar)) {
                        $subQuery->orWhere('id', (int) $buscar);
                    }

                    $subQuery->orWhere('codigo', 'like', "%{$buscar}%")
                        ->orWhere('nombre_completo', 'like', "%{$buscar}%")
                        ->orWhere('email', 'like', "%{$buscar}%")
                        ->orWhere('telefono', 'like', "%{$buscar}%")
                        ->orWhereHas('usuario', function (Builder $usuarioQuery) use ($buscar) {
                            $usuarioQuery->where('nombre', 'like', "%{$buscar}%")
                                ->orWhere('apellido', 'like', "%{$buscar}%")
                                ->orWhereRaw("CONCAT(COALESCE(nombre, ''), ' ', COALESCE(apellido, '')) like ?", ["%{$buscar}%"])
                                ->orWhere('email', 'like', "%{$buscar}%")
                                ->orWhere('telefono', 'like', "%{$buscar}%");
                        });
                });
            })
            ->withCount('items')
            ->orderByDesc('fecha_confirmacion')
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        return view('admin.pedidos.index', [
            'pedidos' => $pedidos,
            'buscar' => $buscar,
            'estado' => $estado,
            'estadosPermitidos' => self::ESTADOS_PERMITIDOS,
            'resumen' => [
                'total_pedidos' => (int) ($resumen->total_pedidos ?? 0),
                'confirmados' => (int) ($resumen->confirmados ?? 0),
                'preparando' => (int) ($resumen->preparando ?? 0),
                'enviados' => (int) ($resumen->enviados ?? 0),
                'entregados' => (int) ($resumen->entregados ?? 0),
                'total_facturado' => (float) ($resumen->total_facturado ?? 0),
            ],
        ]);
    }

    public function show(Pedido $pedido): View|RedirectResponse
    {
        if ($pedido->estado === 'carrito') {
            return redirect()
                ->route('admin.pedidos.index')
                ->with('error', 'Ese pedido todavia es un carrito y no se puede gestionar desde el panel.');
        }

        $pedido->asegurarCodigoVisible();

        $pedido->load([
            'usuario',
            'items.producto.categoria',
            'items.producto.marca',
        ]);

        return view('admin.pedidos.show', [
            'pedido' => $pedido,
            'estadosPermitidos' => self::ESTADOS_PERMITIDOS,
            'lineaEstados' => $this->buildLineaEstados($pedido),
        ]);
    }

    public function updateEstado(Request $request, Pedido $pedido): RedirectResponse
    {
        if ($pedido->estado === 'carrito') {
            return redirect()
                ->route('admin.pedidos.index')
                ->with('error', 'No se puede cambiar el estado de un carrito desde Admin Pedidos.');
        }

        $datos = $request->validate([
            'estado' => ['required', 'string', Rule::in(self::ESTADOS_PERMITIDOS)],
        ]);

        if (! in_array($pedido->estado, self::ESTADOS_PERMITIDOS, true)) {
            return redirect()
                ->route('admin.pedidos.show', $pedido)
                ->with('error', 'El pedido tiene un estado no administrable en esta etapa.');
        }

        if (! $this->esTransicionValida($pedido->estado, $datos['estado'])) {
            return redirect()
                ->route('admin.pedidos.show', $pedido)
                ->with('error', 'La transicion de estado solicitada no es valida.');
        }

        $actualizacion = [
            'estado' => $datos['estado'],
        ];

        foreach ($this->buildEstadoDateUpdates($pedido, $datos['estado']) as $campo => $valor) {
            $actualizacion[$campo] = $valor;
        }

        $pedido->update($actualizacion);

        return redirect()
            ->route('admin.pedidos.show', $pedido)
            ->with('success', 'Estado del pedido actualizado correctamente.');
    }

    public function pdf(Pedido $pedido): View|Response|RedirectResponse
    {
        if ($pedido->estado === 'carrito') {
            return redirect()
                ->route('admin.pedidos.index')
                ->with('error', 'Ese pedido todavia es un carrito y no se puede exportar desde el panel.');
        }

        $pedido->asegurarCodigoVisible();

        $pedido->load([
            'usuario',
            'items.producto.categoria',
            'items.producto.marca',
        ]);

        if (! class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            return redirect()
                ->route('admin.pedidos.show', $pedido)
                ->with('error', 'No hay una libreria PDF instalada en el proyecto para descargar este pedido.');
        }

        try {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML(
                $this->generarHtmlPedidoPdf($pedido)
            )->setPaper('a4', 'portrait');

            $filename = 'pedido-' . $pedido->codigo_visible . '.pdf';

            return response($pdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'private, max-age=0, must-revalidate',
                'Pragma' => 'public',
            ]);
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()
                ->route('admin.pedidos.show', $pedido)
                ->with('error', 'No se pudo generar el PDF del pedido en este momento.');
        }
    }

    private function esTransicionValida(string $estadoActual, string $nuevoEstado): bool
    {
        if ($estadoActual === $nuevoEstado) {
            return true;
        }

        $ordenEstados = array_flip(self::ESTADOS_PERMITIDOS);

        if (! array_key_exists($estadoActual, $ordenEstados) || ! array_key_exists($nuevoEstado, $ordenEstados)) {
            return false;
        }

        return $ordenEstados[$nuevoEstado] >= $ordenEstados[$estadoActual];
    }

    private function buildLineaEstados(Pedido $pedido): array
    {
        $ordenEstados = array_values(self::ESTADOS_PERMITIDOS);
        $posicionActual = array_search($pedido->estado, $ordenEstados, true);

        return collect($ordenEstados)->map(function (string $estado, int $index) use ($pedido, $posicionActual) {
            $campoFecha = match ($estado) {
                'confirmado' => 'fecha_confirmacion',
                'preparando' => 'fecha_preparando',
                'enviado' => 'fecha_enviado',
                'entregado' => 'fecha_entregado',
                default => null,
            };

            $fecha = $campoFecha ? $pedido->getAttribute($campoFecha) : null;

            return [
                'estado' => $estado,
                'titulo' => ucfirst($estado),
                'fecha' => $fecha,
                'actual' => $pedido->estado === $estado,
                'completado' => $posicionActual !== false && $index <= $posicionActual,
                'pendiente' => $posicionActual !== false && $index > $posicionActual,
            ];
        })->all();
    }

    private function buildEstadoDateUpdates(Pedido $pedido, string $nuevoEstado): array
    {
        if (! $this->pedidoTieneEstadoFechas()) {
            return [];
        }

        $updates = [];
        $mapaCampos = [
            'confirmado' => 'fecha_confirmacion',
            'preparando' => 'fecha_preparando',
            'enviado' => 'fecha_enviado',
            'entregado' => 'fecha_entregado',
        ];

        if (isset($mapaCampos[$nuevoEstado])) {
            $campo = $mapaCampos[$nuevoEstado];

            if (! filled($pedido->getAttribute($campo))) {
                $updates[$campo] = now();
            }
        }

        return $updates;
    }

    private function pedidoTieneEstadoFechas(): bool
    {
        return Schema::hasColumn('pedidos', 'fecha_preparando')
            && Schema::hasColumn('pedidos', 'fecha_enviado')
            && Schema::hasColumn('pedidos', 'fecha_entregado');
    }

    private function generarHtmlPedidoPdf(Pedido $pedido): string
    {
        $cliente = $pedido->nombre_completo;

        if (! filled($cliente) && $pedido->usuario) {
            $cliente = trim(($pedido->usuario->nombre ?? '') . ' ' . ($pedido->usuario->apellido ?? ''));
        }

        $filasItems = $pedido->items->map(function ($item) {
            return '
                <tr>
                    <td>' . e($item->producto_nombre ?: ($item->producto?->nombre ?? 'Producto sin nombre')) . '</td>
                    <td>' . e($item->producto_marca ?: ($item->producto?->marca?->nombre ?? 'Sin marca')) . '</td>
                    <td>' . e($item->producto_categoria ?: ($item->producto?->categoria?->nombre ?? 'Sin categoria')) . '</td>
                    <td class="text-right">' . e((string) $item->cantidad) . '</td>
                    <td class="text-right">$' . number_format((float) $item->precio_unitario, 0, ',', '.') . '</td>
                    <td class="text-right"><strong>$' . number_format((float) $item->subtotal, 0, ',', '.') . '</strong></td>
                </tr>
            ';
        })->implode('');

        return '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe de pedido ' . e($pedido->codigo_visible) . '</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; margin: 28px; color: #2c2214; background: #fffdf8; font-size: 12px; }
        .header { width: 100%; margin-bottom: 24px; }
        .header-table { width: 100%; border-collapse: collapse; }
        .header-table td { border: 0; padding: 0; vertical-align: top; }
        h1 { margin: 0 0 6px; font-size: 24px; }
        h2 { margin: 0 0 14px; font-size: 16px; }
        .muted { color: #796754; font-size: 12px; }
        .section { margin-bottom: 22px; padding: 18px 20px; border: 1px solid #e7d8bb; border-radius: 14px; background: #fffaf0; }
        .grid-table { width: 100%; border-collapse: collapse; }
        .grid-table td { width: 50%; padding: 0 12px 12px 0; border: 0; vertical-align: top; }
        .label { display: block; margin-bottom: 4px; font-size: 10px; font-weight: 700; text-transform: uppercase; color: #8a775c; }
        .status-chip { display: inline-block; padding: 8px 12px; border-radius: 999px; border: 1px solid #d8c6a5; background: #f5efe3; color: #6b5941; font-size: 12px; font-weight: 700; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px 12px; border-bottom: 1px solid #eadfc8; text-align: left; font-size: 12px; }
        th { font-size: 10px; text-transform: uppercase; color: #7a6651; background: #f8f2e8; }
        .text-right { text-align: right; }
        .total-row th, .total-row td { font-weight: 700; }
    </style>
</head>
<body>
    <div class="header">
        <table class="header-table">
            <tr>
                <td>
                    <h1>Informe de pedido</h1>
                    <div class="muted">Hierro &amp; Forja</div>
                </td>
                <td class="text-right">
                    <strong>' . e($pedido->codigo_visible) . '</strong><br>
                    <span class="muted">' . e($pedido->fecha_confirmacion?->format('d/m/Y H:i') ?? 'Sin confirmar') . '</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>Datos generales</h2>
        <table class="grid-table">
            <tr>
                <td>
                    <span class="label">Pedido</span>
                    <strong>' . e($pedido->codigo_visible) . '</strong>
                </td>
                <td>
                    <span class="label">Estado actual</span>
                    <span class="status-chip">' . e(ucfirst($pedido->estado)) . '</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Cliente</span>
                    <strong>' . e(filled($cliente) ? $cliente : 'Cliente sin nombre') . '</strong>
                </td>
                <td>
                    <span class="label">Fecha de confirmacion</span>
                    <strong>' . e($pedido->fecha_confirmacion?->format('d/m/Y H:i') ?? '-') . '</strong>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Email</span>
                    <strong>' . e($pedido->email ?: '-') . '</strong>
                </td>
                <td>
                    <span class="label">Telefono</span>
                    <strong>' . e($pedido->telefono ?: '-') . '</strong>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>Datos de entrega</h2>
        <table class="grid-table">
            <tr>
                <td>
                    <span class="label">Direccion</span>
                    <strong>' . e($pedido->direccion ?: '-') . '</strong>
                </td>
                <td>
                    <span class="label">Ciudad</span>
                    <strong>' . e($pedido->ciudad ?: '-') . '</strong>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Provincia</span>
                    <strong>' . e($pedido->provincia ?: '-') . '</strong>
                </td>
                <td>
                    <span class="label">Codigo postal</span>
                    <strong>' . e($pedido->codigo_postal ?: '-') . '</strong>
                </td>
            </tr>' .
            (filled($pedido->observaciones)
                ? '<tr><td colspan="2"><span class="label">Observaciones</span><strong>' . e($pedido->observaciones) . '</strong></td></tr>'
                : '') . '
        </table>
    </div>

    <div class="section">
        <h2>Detalle de productos</h2>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Marca</th>
                    <th>Categoria</th>
                    <th class="text-right">Cantidad</th>
                    <th class="text-right">Precio unitario</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                ' . $filasItems . '
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" class="text-right">Subtotal</th>
                    <td class="text-right">$' . number_format((float) $pedido->subtotal, 0, ',', '.') . '</td>
                </tr>
                <tr>
                    <th colspan="5" class="text-right">Envio</th>
                    <td class="text-right">$' . number_format((float) $pedido->envio, 0, ',', '.') . '</td>
                </tr>
                <tr>
                    <th colspan="5" class="text-right">Descuento</th>
                    <td class="text-right">$' . number_format((float) $pedido->descuento, 0, ',', '.') . '</td>
                </tr>
                <tr class="total-row">
                    <th colspan="5" class="text-right">Total</th>
                    <td class="text-right">$' . number_format((float) $pedido->total, 0, ',', '.') . '</td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>';
    }
}
