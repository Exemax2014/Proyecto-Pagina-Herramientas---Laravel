<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    public function index(): View
    {
        $pedidos = Pedido::query()
            ->with(['usuario'])
            ->where('estado', '!=', 'carrito')
            ->withCount('items')
            ->orderByDesc('fecha_confirmacion')
            ->orderByDesc('id')
            ->paginate(12);

        return view('admin.pedidos.index', compact('pedidos'));
    }

    public function show(Pedido $pedido): View|RedirectResponse
    {
        if ($pedido->estado === 'carrito') {
            return redirect()
                ->route('admin.pedidos.index')
                ->with('error', 'Ese pedido todavia es un carrito y no se puede gestionar desde el panel.');
        }

        $pedido->load([
            'usuario',
            'items.producto.categoria',
            'items.producto.marca',
        ]);

        return view('admin.pedidos.show', [
            'pedido' => $pedido,
            'estadosPermitidos' => self::ESTADOS_PERMITIDOS,
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

        $pedido->update([
            'estado' => $datos['estado'],
        ]);

        return redirect()
            ->route('admin.pedidos.show', $pedido)
            ->with('success', 'Estado del pedido actualizado correctamente.');
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
}
