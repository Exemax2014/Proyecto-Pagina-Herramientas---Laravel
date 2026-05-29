<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class MisComprasController extends Controller
{
    private const ESTADOS_PEDIDO = [
        'confirmado',
        'preparando',
        'enviado',
        'entregado',
    ];

    public function index(): View
    {
        $pedidos = Pedido::query()
            ->where('usuario_id', session('usuario_id'))
            ->where('estado', '!=', 'carrito')
            ->withCount('items')
            ->orderByDesc('fecha_confirmacion')
            ->orderByDesc('id')
            ->paginate(10);

        return view('pages.mis-compras', [
            'pedidos' => $pedidos,
        ]);
    }

    public function show(Pedido $pedido): View
    {
        abort_if(
            $pedido->usuario_id !== (int) session('usuario_id') || $pedido->estado === 'carrito',
            404
        );

        $pedido->load([
            'usuario',
            'items.producto.categoria',
            'items.producto.marca',
            'items.producto.imagenPrincipal',
        ]);

        return view('pages.mis-compra-detalle', [
            'pedido' => $pedido,
            'lineaEstados' => $this->buildLineaEstados($pedido),
        ]);
    }

    private function buildLineaEstados(Pedido $pedido): array
    {
        $posicionActual = array_search($pedido->estado, self::ESTADOS_PEDIDO, true);

        return collect(self::ESTADOS_PEDIDO)->map(function (string $estado, int $index) use ($pedido, $posicionActual) {
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
}
