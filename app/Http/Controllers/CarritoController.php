<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Producto;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarritoController extends Controller
{
    public function index()
    {
        return view('pages.carrito');
    }

    public function obtenerCarrito()
    {
        $usuarioId = session('usuario_id');

        if (! $usuarioId) {
            return response()->json([
                'message' => 'Debes iniciar sesion para acceder al carrito.',
            ], 401);
        }

        $pedido = $this->obtenerCarritoActivo($usuarioId);

        return response()->json([
            'carrito' => $pedido
                ? $this->serializarCarrito($pedido)
                : $this->serializarCarritoVacio($usuarioId),
        ]);
    }

    public function agregar(Request $request)
    {
        $usuarioId = session('usuario_id');

        if (! $usuarioId) {
            return response()->json([
                'message' => 'Debes iniciar sesion para agregar productos al carrito.',
            ], 401);
        }

        $datos = $request->validate([
            'producto_id' => ['required', 'exists:productos,id'],
            'cantidad' => ['required', 'integer', 'min:1'],
        ]);

        $pedido = $this->obtenerOCrearCarrito($usuarioId);

        $producto = Producto::with(['categoria', 'marca'])
            ->where('activo', true)
            ->find($datos['producto_id']);

        if (! $producto) {
            return response()->json([
                'message' => 'El producto seleccionado no esta disponible.',
            ], 422);
        }

        $item = $pedido->items()
            ->where('producto_id', $producto->id)
            ->first();

        $cantidadTotal = ($item?->cantidad ?? 0) + $datos['cantidad'];

        if ($producto->stock < $cantidadTotal) {
            return response()->json([
                'message' => 'No hay stock suficiente para la cantidad solicitada.',
                'stock_disponible' => $producto->stock,
            ], 422);
        }

        if ($item) {
            $item->cantidad = $cantidadTotal;
            $item->precio_unitario = $producto->precio;
            $item->subtotal = $item->cantidad * $item->precio_unitario;
            $item->producto_nombre = $producto->nombre;
            $item->producto_marca = $producto->marca?->nombre;
            $item->producto_categoria = $producto->categoria?->nombre;
            $item->save();
        } else {
            PedidoItem::create([
                'pedido_id' => $pedido->id,
                'producto_id' => $producto->id,
                'producto_nombre' => $producto->nombre,
                'producto_marca' => $producto->marca?->nombre,
                'producto_categoria' => $producto->categoria?->nombre,
                'cantidad' => $datos['cantidad'],
                'precio_unitario' => $producto->precio,
                'subtotal' => $datos['cantidad'] * $producto->precio,
            ]);
        }

        $pedido->refresh();
        $this->recalcularTotal($pedido);
        $pedido->load(['items.producto.categoria', 'items.producto.marca', 'items.producto.imagenPrincipal']);

        return response()->json([
            'message' => 'Producto agregado al carrito correctamente.',
            'carrito' => $this->serializarCarrito($pedido),
        ]);
    }

    public function eliminar($itemId)
    {
        $usuarioId = session('usuario_id');

        if (! $usuarioId) {
            return response()->json([
                'message' => 'Debes iniciar sesion para modificar el carrito.',
            ], 401);
        }

        $pedido = $this->obtenerCarritoActivo($usuarioId);

        if (! $pedido) {
            return response()->json([
                'message' => 'No se encontro un carrito activo para modificar.',
                'carrito' => $this->serializarCarritoVacio($usuarioId),
            ], 404);
        }

        $item = $pedido->items()
            ->where('id', $itemId)
            ->first();

        if (! $item) {
            return response()->json([
                'message' => 'El item no pertenece a tu carrito activo.',
            ], 404);
        }

        $item->delete();

        $pedido->refresh();

        if (! $pedido->items()->exists()) {
            $pedido->delete();

            return response()->json([
                'message' => 'Item eliminado del carrito correctamente.',
                'carrito' => $this->serializarCarritoVacio($usuarioId),
            ]);
        }

        $this->recalcularTotal($pedido);
        $pedido->load(['items.producto.categoria', 'items.producto.marca', 'items.producto.imagenPrincipal']);

        return response()->json([
            'message' => 'Item eliminado del carrito correctamente.',
            'carrito' => $this->serializarCarrito($pedido),
        ]);
    }

    public function confirmar()
    {
        $usuarioId = session('usuario_id');

        if (! $usuarioId) {
            return response()->json([
                'message' => 'Debes iniciar sesion para confirmar el carrito.',
            ], 401);
        }

        $pedidoConfirmado = DB::transaction(function () use ($usuarioId) {
            $pedido = Pedido::query()
                ->where('usuario_id', $usuarioId)
                ->where('estado', 'carrito')
                ->latest('id')
                ->lockForUpdate()
                ->first();

            if (! $pedido) {
                return response()->json([
                    'message' => 'No hay un carrito con productos para confirmar.',
                ], 422);
            }

            $pedido->load('items');

            if ($pedido->items->isEmpty()) {
                $pedido->delete();

                return response()->json([
                    'message' => 'No puedes confirmar un carrito vacio.',
                ], 422);
            }

            $productos = Producto::with(['categoria', 'marca'])
                ->whereIn('id', $pedido->items->pluck('producto_id')->filter())
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($pedido->items as $item) {
                $producto = $productos->get($item->producto_id);

                if (! $producto || ! $producto->activo) {
                    return response()->json([
                        'message' => "El producto {$item->producto_nombre} ya no esta disponible.",
                    ], 422);
                }

                if ($producto->stock < $item->cantidad) {
                    return response()->json([
                        'message' => "No hay stock suficiente para {$item->producto_nombre}.",
                        'stock_disponible' => $producto->stock,
                    ], 422);
                }
            }

            foreach ($pedido->items as $item) {
                $producto = $productos->get($item->producto_id);

                $item->precio_unitario = $producto->precio;
                $item->subtotal = $item->cantidad * $item->precio_unitario;
                $item->producto_nombre = $producto->nombre;
                $item->producto_marca = $producto->marca?->nombre;
                $item->producto_categoria = $producto->categoria?->nombre;
                $item->save();

                $producto->stock -= $item->cantidad;
                $producto->ventas += $item->cantidad;
                $producto->save();
            }

            $this->recalcularTotal($pedido);

            $pedido->estado = 'confirmado';
            $pedido->fecha_confirmacion = now();
            $pedido->save();

            $pedido->load(['items.producto.categoria', 'items.producto.marca', 'items.producto.imagenPrincipal']);

            return $pedido;
        });

        if ($pedidoConfirmado instanceof \Illuminate\Http\JsonResponse) {
            return $pedidoConfirmado;
        }

        return response()->json([
            'message' => 'Carrito confirmado correctamente.',
            'pedido' => [
                'id' => $pedidoConfirmado->id,
                'estado' => $pedidoConfirmado->estado,
                'fecha_confirmacion' => optional($pedidoConfirmado->fecha_confirmacion)->toDateTimeString(),
                'total' => $pedidoConfirmado->total,
                'items_count' => $pedidoConfirmado->items->count(),
            ],
        ]);
    }

    public function recalcularTotal(Pedido $pedido)
    {
        $pedido->loadMissing('items');

        $subtotal = $pedido->items->sum('subtotal');
        $total = $subtotal + (float) $pedido->envio - (float) $pedido->descuento;

        $pedido->subtotal = $subtotal;
        $pedido->total = $total;
        $pedido->save();

        return $pedido->fresh(['items.producto.categoria', 'items.producto.marca', 'items.producto.imagenPrincipal']);
    }

    protected function obtenerCarritoActivo(int $usuarioId): ?Pedido
    {
        return Pedido::with(['items.producto.categoria', 'items.producto.marca', 'items.producto.imagenPrincipal'])
            ->where('usuario_id', $usuarioId)
            ->where('estado', 'carrito')
            ->latest('id')
            ->first();
    }

    protected function obtenerOCrearCarrito(int $usuarioId): Pedido
    {
        $pedido = $this->obtenerCarritoActivo($usuarioId);

        if ($pedido) {
            return $pedido;
        }

        $usuario = Usuario::findOrFail($usuarioId);

        return Pedido::create([
            'usuario_id' => $usuario->id,
            'nombre_completo' => trim($usuario->nombre . ' ' . $usuario->apellido),
            'email' => $usuario->email,
            'telefono' => $usuario->telefono,
            'direccion' => $usuario->direccion,
            'ciudad' => $usuario->ciudad,
            'provincia' => $usuario->provincia,
            'codigo_postal' => $usuario->codigo_postal,
            'estado' => 'carrito',
            'subtotal' => 0,
            'total' => 0,
        ])->load(['items.producto.categoria', 'items.producto.marca', 'items.producto.imagenPrincipal']);
    }

    protected function serializarCarritoVacio(?int $usuarioId = null): array
    {
        return [
            'id' => null,
            'usuario_id' => $usuarioId,
            'estado' => 'carrito',
            'subtotal' => 0,
            'envio' => 0,
            'descuento' => 0,
            'total' => 0,
            'cantidad_total' => 0,
            'fecha_confirmacion' => null,
            'items' => [],
        ];
    }

    protected function serializarCarrito(Pedido $pedido): array
    {
        $items = $pedido->items->map(function (PedidoItem $item) {
            return [
                'id' => $item->id,
                'producto_id' => $item->producto_id,
                'nombre' => $item->producto_nombre ?: 'Producto sin nombre',
                'marca' => $item->producto_marca ?: 'Sin marca',
                'categoria' => $item->producto_categoria ?: 'Sin categoria',
                'precio_unitario' => (float) $item->precio_unitario,
                'cantidad' => (int) $item->cantidad,
                'subtotal' => (float) $item->subtotal,
                'imagen' => $item->producto?->imagenPrincipal?->url
                    ? asset($item->producto->imagenPrincipal->url)
                    : asset('img/producto-sin-imagen.png'),
            ];
        })->values();

        return [
            'id' => $pedido->id,
            'usuario_id' => $pedido->usuario_id,
            'estado' => $pedido->estado,
            'subtotal' => (float) $pedido->subtotal,
            'envio' => (float) $pedido->envio,
            'descuento' => (float) $pedido->descuento,
            'total' => (float) $pedido->total,
            'cantidad_total' => $items->sum('cantidad'),
            'fecha_confirmacion' => optional($pedido->fecha_confirmacion)->toDateTimeString(),
            'items' => $items,
        ];
    }
}
