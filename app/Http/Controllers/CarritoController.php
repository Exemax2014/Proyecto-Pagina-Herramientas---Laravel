<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Producto;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CarritoController extends Controller
{
    private const ESTADOS_CONFIRMACION = [
        'confirmado',
        'preparando',
        'enviado',
    ];

    public function index()
    {
        return view('pages.carrito');
    }

    public function datos(Request $request)
    {
        $usuario = Usuario::findOrFail(session('usuario_id'));
        $pedido = $this->obtenerCarritoActivo($usuario->id);

        return view('pages.carrito-datos', [
            'usuario' => $usuario,
            'carrito' => $pedido
                ? $this->serializarCarrito($pedido)
                : $this->serializarCarritoVacio($usuario->id),
            'metodoPagoSeleccionado' => $this->normalizarMetodoPago(
                old('metodo_pago', $request->query('metodo_pago', 'tarjeta'))
            ),
            'modoEntregaSeleccionado' => $this->normalizarModoEntrega(
                old('modo_entrega', 'retiro_local')
            ),
        ]);
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

        $resultado = $this->guardarItemDeCarrito(
            pedido: $pedido,
            producto: $producto,
            item: $item,
            cantidadObjetivo: ($item?->cantidad ?? 0) + $datos['cantidad']
        );

        if (! $resultado['ok']) {
            return response()->json([
                'message' => $resultado['message'],
                'stock_disponible' => $producto->stock,
            ], 422);
        }

        $pedido->refresh();
        $this->recalcularTotal($pedido);
        $pedido->load(['items.producto.categoria', 'items.producto.marca', 'items.producto.imagenPrincipal']);

        return response()->json([
            'message' => $resultado['message'] ?: 'Producto agregado al carrito correctamente.',
            'warnings' => $resultado['warning'] ? [$resultado['warning']] : [],
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

    public function actualizarCantidad(Request $request, $itemId)
    {
        $usuarioId = session('usuario_id');

        if (! $usuarioId) {
            return response()->json([
                'message' => 'Debes iniciar sesion para modificar el carrito.',
            ], 401);
        }

        $datos = $request->validate([
            'cantidad' => ['required', 'integer', 'min:1'],
        ]);

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

        $producto = Producto::with(['categoria', 'marca'])
            ->where('activo', true)
            ->find($item->producto_id);

        if (! $producto) {
            return response()->json([
                'message' => 'El producto seleccionado no esta disponible.',
            ], 422);
        }

        $resultado = $this->guardarItemDeCarrito(
            pedido: $pedido,
            producto: $producto,
            item: $item,
            cantidadObjetivo: $datos['cantidad']
        );

        if (! $resultado['ok']) {
            return response()->json([
                'message' => $resultado['message'],
                'stock_disponible' => $producto->stock,
            ], 422);
        }

        $pedido->refresh();
        $this->recalcularTotal($pedido);
        $pedido->load(['items.producto.categoria', 'items.producto.marca', 'items.producto.imagenPrincipal']);

        return response()->json([
            'message' => $resultado['message'] ?: 'Cantidad actualizada correctamente.',
            'warnings' => $resultado['warning'] ? [$resultado['warning']] : [],
            'carrito' => $this->serializarCarrito($pedido),
        ]);
    }

    public function migrar(Request $request)
    {
        $usuarioId = session('usuario_id');

        if (! $usuarioId) {
            return response()->json([
                'message' => 'Debes iniciar sesion para migrar el carrito.',
            ], 401);
        }

        $datos = $request->validate([
            'items' => ['required', 'array'],
            'items.*.producto_id' => ['required', 'integer', 'exists:productos,id'],
            'items.*.cantidad' => ['required', 'integer', 'min:1'],
        ]);

        $pedido = $this->obtenerOCrearCarrito($usuarioId);
        $warnings = [];
        $migrados = 0;
        $remainingItems = [];

        foreach ($datos['items'] as $itemData) {
            $producto = Producto::with(['categoria', 'marca'])
                ->find($itemData['producto_id']);

            if (! $producto || ! $producto->activo) {
                $warnings[] = 'Un producto no pudo migrarse porque ya no esta disponible.';
                $remainingItems[] = [
                    'producto_id' => $itemData['producto_id'],
                    'cantidad' => $itemData['cantidad'],
                ];
                continue;
            }

            if ($producto->stock < 1) {
                $warnings[] = "El producto {$producto->nombre} no pudo migrarse porque no tiene stock.";
                $remainingItems[] = [
                    'producto_id' => $itemData['producto_id'],
                    'cantidad' => $itemData['cantidad'],
                ];
                continue;
            }

            $itemExistente = $pedido->items()
                ->where('producto_id', $producto->id)
                ->first();

            $resultado = $this->guardarItemDeCarrito(
                pedido: $pedido,
                producto: $producto,
                item: $itemExistente,
                cantidadObjetivo: ($itemExistente?->cantidad ?? 0) + $itemData['cantidad']
            );

            if ($resultado['ok']) {
                $migrados++;
            }

            if ($resultado['warning']) {
                $warnings[] = $resultado['warning'];
            }

            if (! $resultado['ok'] && $resultado['message']) {
                $warnings[] = $resultado['message'];
                $remainingItems[] = [
                    'producto_id' => $itemData['producto_id'],
                    'cantidad' => $itemData['cantidad'],
                ];
            }
        }

        $pedido->refresh();

        if (! $pedido->items()->exists()) {
            $pedido->delete();

            return response()->json([
                'message' => 'No se pudieron migrar productos al carrito persistido.',
                'warnings' => $warnings,
                'carrito' => $this->serializarCarritoVacio($usuarioId),
                'migrados' => $migrados,
                'remaining_items' => $remainingItems,
            ]);
        }

        $this->recalcularTotal($pedido);
        $pedido->load(['items.producto.categoria', 'items.producto.marca', 'items.producto.imagenPrincipal']);

        return response()->json([
            'message' => $migrados > 0
                ? 'El carrito temporal se migro correctamente.'
                : 'No se pudieron migrar productos al carrito persistido.',
            'warnings' => $warnings,
            'carrito' => $this->serializarCarrito($pedido),
            'migrados' => $migrados,
            'remaining_items' => $remainingItems,
        ]);
    }

    public function confirmar(Request $request)
    {
        $usuarioId = session('usuario_id');

        if (! $usuarioId) {
            return $this->checkoutErrorResponse($request, 'Debes iniciar sesion para confirmar el carrito.', 401);
        }

        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'dni' => ['required', 'string', 'max:20'],
            'telefono' => ['required', 'string', 'max:50'],
            'direccion' => ['required', 'string', 'max:255'],
            'ciudad' => ['required', 'string', 'max:100'],
            'provincia' => ['required', 'string', 'max:100'],
            'codigo_postal' => ['required', 'string', 'max:20'],
            'metodo_pago' => ['required', 'in:tarjeta,efectivo'],
            'modo_entrega' => ['required', 'in:retiro_local,envio_domicilio'],
        ]);

        $pedidoConfirmado = DB::transaction(function () use ($usuarioId, $datos, $request) {
            $pedido = Pedido::query()
                ->where('usuario_id', $usuarioId)
                ->where('estado', 'carrito')
                ->latest('id')
                ->lockForUpdate()
                ->first();

            if (! $pedido) {
                return $this->checkoutErrorResponse($request, 'No hay un carrito con productos para confirmar.');
            }

            $pedido->load('items');

            if ($pedido->items->isEmpty()) {
                $pedido->delete();

                return $this->checkoutErrorResponse($request, 'No puedes confirmar un carrito vacio.');
            }

            $productos = Producto::with(['categoria', 'marca'])
                ->whereIn('id', $pedido->items->pluck('producto_id')->filter())
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($pedido->items as $item) {
                $producto = $productos->get($item->producto_id);

                if (! $producto || ! $producto->activo) {
                    return $this->checkoutErrorResponse($request, "El producto {$item->producto_nombre} ya no esta disponible.");
                }

                if ($producto->stock < $item->cantidad) {
                    return $this->checkoutErrorResponse(
                        $request,
                        "No hay stock suficiente para {$item->producto_nombre}.",
                        422,
                        ['stock_disponible' => $producto->stock]
                    );
                }
            }

            $pedido->nombre_completo = trim($datos['nombre'] . ' ' . $datos['apellido']);
            $pedido->email = $datos['email'];
            $pedido->dni = $datos['dni'];
            $pedido->telefono = $datos['telefono'];
            $pedido->direccion = $datos['direccion'];
            $pedido->ciudad = $datos['ciudad'];
            $pedido->provincia = $datos['provincia'];
            $pedido->codigo_postal = $datos['codigo_postal'];
            $pedido->metodo_pago = $this->normalizarMetodoPago($datos['metodo_pago']);
            $pedido->modo_entrega = $this->normalizarModoEntrega($datos['modo_entrega']);

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
            $pedido->asegurarCodigoVisible();

            $pedido->load(['items.producto.categoria', 'items.producto.marca', 'items.producto.imagenPrincipal']);

            return $pedido;
        });

        if ($pedidoConfirmado instanceof \Illuminate\Http\JsonResponse
            || $pedidoConfirmado instanceof \Illuminate\Http\RedirectResponse) {
            return $pedidoConfirmado;
        }

        if (! $request->expectsJson()) {
            return redirect()->route('carrito.confirmado', $pedidoConfirmado);
        }

        return response()->json([
            'message' => 'Carrito confirmado correctamente.',
            'pedido' => [
                'id' => $pedidoConfirmado->id,
                'codigo_visible' => $pedidoConfirmado->codigo_visible,
                'estado' => $pedidoConfirmado->estado,
                'fecha_confirmacion' => optional($pedidoConfirmado->fecha_confirmacion)->toDateTimeString(),
                'total' => $pedidoConfirmado->total,
                'items_count' => $pedidoConfirmado->items->count(),
            ],
            'redirect_url' => route('carrito.confirmado', $pedidoConfirmado),
        ]);
    }

    public function confirmado(Pedido $pedido)
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

        $pedido->asegurarCodigoVisible();

        return view('pages.carrito-confirmado', [
            'pedido' => $pedido,
            'lineaEstados' => $this->buildLineaEstados($pedido),
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
                'descripcion' => $item->producto?->descripcion
                    ? Str::limit(trim(strip_tags($item->producto->descripcion)), 140)
                    : null,
                'precio_unitario' => (float) $item->precio_unitario,
                'cantidad' => (int) $item->cantidad,
                'subtotal' => (float) $item->subtotal,
                'imagen' => $item->producto?->imagenPrincipal?->url
                    ? asset($item->producto->imagenPrincipal->url)
                    : asset('img/producto-sin-imagen.svg'),
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
            'metodo_pago' => $pedido->metodo_pago,
            'modo_entrega' => $pedido->modo_entrega,
            'items' => $items,
        ];
    }

    protected function guardarItemDeCarrito(Pedido $pedido, Producto $producto, ?PedidoItem $item, int $cantidadObjetivo): array
    {
        if ($producto->stock < 1) {
            return [
                'ok' => false,
                'message' => "El producto {$producto->nombre} no tiene stock disponible.",
                'warning' => null,
            ];
        }

        $cantidadFinal = min($cantidadObjetivo, $producto->stock);

        if ($cantidadFinal < 1) {
            return [
                'ok' => false,
                'message' => "El producto {$producto->nombre} no tiene stock disponible.",
                'warning' => null,
            ];
        }

        $warning = null;
        $message = null;

        if ($cantidadFinal < $cantidadObjetivo) {
            $warning = "El producto {$producto->nombre} fue ajustado al stock disponible.";
            $message = 'No se puede agregar mas unidades porque supera el stock disponible.';
        }

        $item ??= new PedidoItem([
            'pedido_id' => $pedido->id,
            'producto_id' => $producto->id,
        ]);

        $item->pedido_id = $pedido->id;
        $item->producto_id = $producto->id;
        $item->cantidad = $cantidadFinal;
        $item->precio_unitario = $producto->precio;
        $item->subtotal = $item->cantidad * $item->precio_unitario;
        $item->producto_nombre = $producto->nombre;
        $item->producto_marca = $producto->marca?->nombre;
        $item->producto_categoria = $producto->categoria?->nombre;
        $item->save();

        return [
            'ok' => true,
            'message' => $message,
            'warning' => $warning,
        ];
    }

    protected function checkoutErrorResponse(Request $request, string $message, int $status = 422, array $extra = [])
    {
        if ($request->expectsJson()) {
            return response()->json(array_merge([
                'message' => $message,
            ], $extra), $status);
        }

        return back()
            ->withErrors(['checkout' => $message])
            ->withInput();
    }

    protected function normalizarMetodoPago(?string $metodoPago): string
    {
        return in_array($metodoPago, ['tarjeta', 'efectivo'], true)
            ? $metodoPago
            : 'tarjeta';
    }

    protected function normalizarModoEntrega(?string $modoEntrega): string
    {
        return in_array($modoEntrega, ['retiro_local', 'envio_domicilio'], true)
            ? $modoEntrega
            : 'retiro_local';
    }

    protected function buildLineaEstados(Pedido $pedido): array
    {
        $posicionActual = array_search($pedido->estado, self::ESTADOS_CONFIRMACION, true);

        return collect(self::ESTADOS_CONFIRMACION)->map(function (string $estado, int $index) use ($pedido, $posicionActual) {
            $campoFecha = match ($estado) {
                'confirmado' => 'fecha_confirmacion',
                'preparando' => 'fecha_preparando',
                'enviado' => 'fecha_enviado',
                default => null,
            };

            return [
                'estado' => $estado,
                'titulo' => ucfirst(str_replace('_', ' ', $estado)),
                'fecha' => $campoFecha ? $pedido->getAttribute($campoFecha) : null,
                'actual' => $pedido->estado === $estado,
                'completado' => $posicionActual !== false && $index <= $posicionActual,
                'pendiente' => $posicionActual !== false && $index > $posicionActual,
            ];
        })->all();
    }
}
