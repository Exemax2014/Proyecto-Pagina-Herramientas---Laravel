<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
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
