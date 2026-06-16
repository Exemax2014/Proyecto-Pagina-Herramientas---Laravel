<?php

namespace App\Http\Controllers;

use App\Models\Domicilio;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Producto;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CarritoController extends Controller
{
    private const CHECKOUT_SESSION_KEY = 'checkout_data';
    private const LOCAL_PICKUP_ADDRESS = 'Don Bosco 985, Corrientes';
    private const LOCAL_PICKUP_PROVINCE = 'Corrientes';
    private const SHIPPING_SAME_PROVINCE = 20000.0;
    private const SHIPPING_OTHER_PROVINCE = 40000.0;

    private const ESTADOS_CONFIRMACION = [
        'confirmado',
        'preparando',
        'enviado',
    ];

    public function index()
    {
        if ($response = $this->bloquearComprasAdmin()) {
            return $response;
        }

        return view('pages.carrito');
    }

    public function datos(Request $request)
    {
        $usuario = Usuario::findOrFail(session('usuario_id'));

        if ($response = $this->bloquearComprasAdmin($usuario, $request)) {
            return $response;
        }

        if ($redirect = $this->redirectSiPerfilIncompleto($usuario)) {
            return $redirect;
        }

        $pedido = $this->obtenerCarritoActivo($usuario->id);
        $checkoutData = $this->getCheckoutData();
        $domicilioPrincipal = $this->obtenerDomicilioPrincipalUsuario($usuario);
        $domicilios = $usuario->domiciliosActivos()
            ->orderByDesc('es_principal')
            ->latest('id')
            ->get();
        $prefillAddress = $checkoutData['nuevo_domicilio'] ?? [
            'calle' => '',
            'numero' => '',
            'piso_departamento' => '',
            'ciudad' => '',
            'provincia' => '',
            'codigo_postal' => '',
            'referencia' => '',
        ];

        $domicilioOpcion = old(
            'entrega_opcion',
            $checkoutData['domicilio_opcion'] ?? ($domicilioPrincipal ? 'domicilio_existente' : 'domicilio_nuevo')
        );

        if (! in_array($domicilioOpcion, ['domicilio_existente', 'domicilio_nuevo', 'retiro_local'], true)) {
            $domicilioOpcion = $domicilioPrincipal ? 'domicilio_existente' : 'domicilio_nuevo';
        }

        if (! $domicilioPrincipal && $domicilioOpcion === 'domicilio_existente') {
            $domicilioOpcion = 'domicilio_nuevo';
        }

        $domicilioSeleccionadoId = (int) old(
            'domicilio_id',
            $checkoutData['domicilio_id'] ?? $domicilioPrincipal?->id
        );

        if ($domicilioSeleccionadoId > 0 && ! $domicilios->contains('id', $domicilioSeleccionadoId)) {
            $domicilioSeleccionadoId = (int) ($domicilioPrincipal?->id ?? 0);
        }

        $carrito = $pedido
            ? $this->serializarCarrito($pedido)
            : $this->serializarCarritoVacio($usuario->id);

        $domicilioSeleccionado = $domicilios->firstWhere('id', $domicilioSeleccionadoId) ?: $domicilioPrincipal;
        $domicilioPreview = match ($domicilioOpcion) {
            'domicilio_existente' => $domicilioSeleccionado ? $this->serializarDomicilio($domicilioSeleccionado) : null,
            'domicilio_nuevo' => $this->buildNuevoDomicilioSnapshot($prefillAddress),
            'retiro_local' => $this->buildRetiroLocalSnapshot(),
            default => null,
        };

        $carrito = $this->aplicarEnvioAlCarrito(
            $carrito,
            $this->calcularCostoEnvioCheckout(
                $domicilioOpcion === 'retiro_local' ? 'retiro_local' : 'envio',
                $domicilioPreview
            )
        );

        return view('pages.carrito-datos', [
            'usuario' => $usuario,
            'domicilios' => $domicilios,
            'domicilioActual' => $domicilioPrincipal,
            'carrito' => $carrito,
            'domicilioOpcion' => $domicilioOpcion,
            'nuevoDomicilio' => array_merge($prefillAddress, [
                'calle' => old('calle', $prefillAddress['calle'] ?? ''),
                'numero' => old('numero', $prefillAddress['numero'] ?? ''),
                'piso_departamento' => old('piso_departamento', $prefillAddress['piso_departamento'] ?? ''),
                'ciudad' => old('ciudad', $prefillAddress['ciudad'] ?? ''),
                'provincia' => old('provincia', $prefillAddress['provincia'] ?? ''),
                'codigo_postal' => old('codigo_postal', $prefillAddress['codigo_postal'] ?? ''),
                'referencia' => old('referencia', $prefillAddress['referencia'] ?? ''),
            ]),
            'domicilioSeleccionadoId' => $domicilioSeleccionadoId,
            'direccionLocal' => self::LOCAL_PICKUP_ADDRESS,
            'provinciaLocal' => self::LOCAL_PICKUP_PROVINCE,
            'shippingSameProvince' => self::SHIPPING_SAME_PROVINCE,
            'shippingOtherProvince' => self::SHIPPING_OTHER_PROVINCE,
        ]);
    }

    public function guardarDatos(Request $request)
    {
        $usuario = Usuario::findOrFail(session('usuario_id'));

        if ($response = $this->bloquearComprasAdmin($usuario, $request)) {
            return $response;
        }

        if ($redirect = $this->redirectSiPerfilIncompleto($usuario)) {
            return $redirect;
        }

        $pedido = $this->obtenerCarritoActivo($usuario->id);

        if (! $pedido || $pedido->items->isEmpty()) {
            return redirect()
                ->route('carrito')
                ->withErrors(['checkout' => 'No hay un carrito con productos para continuar el checkout.']);
        }

        $validated = $request->validate([
            'entrega_opcion' => ['required', 'in:domicilio_existente,domicilio_nuevo,retiro_local'],
            'domicilio_id' => ['nullable', 'integer'],
            'calle' => ['nullable', 'string', 'max:120'],
            'numero' => ['nullable', 'string', 'max:40'],
            'piso_departamento' => ['nullable', 'string', 'max:80'],
            'ciudad' => ['nullable', 'string', 'max:100'],
            'provincia' => ['nullable', 'string', 'max:100'],
            'codigo_postal' => ['nullable', 'string', 'max:20'],
            'referencia' => ['nullable', 'string', 'max:255'],
        ]);

        $domicilioOpcion = $validated['entrega_opcion'];
        $selectedDomicilio = null;
        $nuevoDomicilioData = [
            'calle' => trim((string) ($validated['calle'] ?? '')),
            'numero' => trim((string) ($validated['numero'] ?? '')),
            'piso_departamento' => trim((string) ($validated['piso_departamento'] ?? '')),
            'ciudad' => trim((string) ($validated['ciudad'] ?? '')),
            'provincia' => trim((string) ($validated['provincia'] ?? '')),
            'codigo_postal' => trim((string) ($validated['codigo_postal'] ?? '')),
            'referencia' => trim((string) ($validated['referencia'] ?? '')),
        ];

        if ($domicilioOpcion === 'retiro_local') {
            session([
                self::CHECKOUT_SESSION_KEY => $this->enriquecerCheckoutDataConEnvio([
                    'modo_entrega' => 'retiro_local',
                    'domicilio_opcion' => 'retiro_local',
                    'domicilio_id' => null,
                    'nuevo_domicilio' => [
                        'calle' => '',
                        'numero' => '',
                        'piso_departamento' => '',
                        'ciudad' => '',
                        'provincia' => '',
                        'codigo_postal' => '',
                        'referencia' => '',
                    ],
                    'domicilio_snapshot' => $this->buildRetiroLocalSnapshot(),
                ]),
            ]);

            return redirect()->route('carrito.confirmacion');
        }

        if ($domicilioOpcion === 'domicilio_existente') {
            $selectedDomicilio = $usuario->domiciliosActivos()
                ->where('id', $validated['domicilio_id'] ?? 0)
                ->first();

            if (! $selectedDomicilio || $selectedDomicilio->usuario_id !== $usuario->id) {
                return back()
                    ->withErrors(['checkout' => 'Selecciona un domicilio registrado valido para continuar.'])
                    ->withInput();
            }
        } else {
            foreach (['calle', 'numero', 'ciudad', 'provincia', 'codigo_postal'] as $campo) {
                if ($nuevoDomicilioData[$campo] === '') {
                    return back()
                        ->withErrors(['checkout' => 'Completa calle, numero, ciudad, provincia y codigo postal para usar un domicilio nuevo.'])
                        ->withInput();
                }
            }

            $selectedDomicilio = null;
        }

        session([
            self::CHECKOUT_SESSION_KEY => $this->enriquecerCheckoutDataConEnvio([
                'modo_entrega' => 'envio',
                'domicilio_opcion' => $domicilioOpcion,
                'domicilio_id' => $selectedDomicilio?->id,
                'nuevo_domicilio' => $nuevoDomicilioData,
                'domicilio_snapshot' => $selectedDomicilio
                    ? $this->serializarDomicilio($selectedDomicilio)
                    : $this->buildNuevoDomicilioSnapshot($nuevoDomicilioData),
            ]),
        ]);

        return redirect()->route('carrito.confirmacion');
    }

    public function confirmacion()
    {
        $usuario = Usuario::findOrFail(session('usuario_id'));

        if ($response = $this->bloquearComprasAdmin($usuario, request())) {
            return $response;
        }

        if ($redirect = $this->redirectSiPerfilIncompleto($usuario)) {
            return $redirect;
        }

        $pedido = $this->obtenerCarritoActivo($usuario->id);
        $checkoutData = $this->enriquecerCheckoutDataConEnvio($this->getCheckoutData());

        if (! $pedido || $pedido->items->isEmpty()) {
            return redirect()
                ->route('carrito')
                ->withErrors(['checkout' => 'No hay un carrito con productos para continuar el checkout.']);
        }

        if (! $checkoutData || empty($checkoutData['modo_entrega'])) {
            return redirect()
                ->route('carrito.datos')
                ->withErrors(['checkout' => 'Primero debes confirmar tus datos de entrega.']);
        }

        session([self::CHECKOUT_SESSION_KEY => $checkoutData]);

        return view('pages.carrito-confirmacion', [
            'usuario' => $usuario,
            'carrito' => $this->aplicarEnvioAlCarrito(
                $this->serializarCarrito($pedido),
                (float) ($checkoutData['costo_envio'] ?? 0)
            ),
            'modoEntregaSeleccionado' => $checkoutData['modo_entrega'] ?? 'envio',
            'metodoPagoSeleccionado' => $this->normalizarMetodoPago(old('metodo_pago', 'tarjeta')),
            'domicilioSeleccionado' => $checkoutData['domicilio_snapshot'] ?? null,
            'direccionLocal' => self::LOCAL_PICKUP_ADDRESS,
            'observaciones' => old('observaciones', $pedido->observaciones),
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

        $usuario = Usuario::findOrFail($usuarioId);

        if ($response = $this->bloquearComprasAdmin($usuario, request())) {
            return $response;
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

        $usuario = Usuario::findOrFail($usuarioId);

        if ($response = $this->bloquearComprasAdmin($usuario, $request)) {
            return $response;
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

        $usuario = Usuario::findOrFail($usuarioId);

        if ($response = $this->bloquearComprasAdmin($usuario, request())) {
            return $response;
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

        $usuario = Usuario::findOrFail($usuarioId);

        if ($response = $this->bloquearComprasAdmin($usuario, $request)) {
            return $response;
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

        $usuario = Usuario::findOrFail($usuarioId);

        if ($response = $this->bloquearComprasAdmin($usuario, $request)) {
            return $response;
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

        $usuario = Usuario::findOrFail($usuarioId);

        if ($response = $this->bloquearComprasAdmin($usuario, $request)) {
            return $response;
        }

        if ($redirect = $this->redirectSiPerfilIncompleto($usuario)) {
            return $redirect;
        }

        $checkoutData = $this->enriquecerCheckoutDataConEnvio($this->getCheckoutData());

        if (! $checkoutData || empty($checkoutData['modo_entrega'])) {
            return $this->checkoutErrorResponse(
                $request,
                'Debes completar el paso de datos y entrega antes de confirmar el pedido.'
            );
        }

        $datos = $request->validate([
            'metodo_pago' => ['required', 'in:tarjeta,efectivo'],
            'observaciones' => ['nullable', 'string', 'max:500'],
        ]);

        $pedidoConfirmado = DB::transaction(function () use ($usuarioId, $usuario, $datos, $checkoutData, $request) {
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

            $domicilioSnapshot = $checkoutData['domicilio_snapshot'] ?? null;
            $esRetiroLocal = ($checkoutData['modo_entrega'] ?? 'envio') === 'retiro_local';

            $pedido->nombre_completo = trim($usuario->nombre . ' ' . $usuario->apellido);
            $pedido->email = $usuario->email;
            $pedido->dni = $usuario->dni;
            $pedido->telefono = $usuario->telefono;
            $pedido->direccion = ! $esRetiroLocal
                ? $this->formatearDomicilioLinea($domicilioSnapshot)
                : null;
            $pedido->ciudad = ! $esRetiroLocal
                ? ($domicilioSnapshot['ciudad'] ?? null)
                : null;
            $pedido->provincia = ! $esRetiroLocal
                ? ($domicilioSnapshot['provincia'] ?? null)
                : null;
            $pedido->codigo_postal = ! $esRetiroLocal
                ? ($domicilioSnapshot['codigo_postal'] ?? null)
                : null;
            $pedido->metodo_pago = $this->normalizarMetodoPago($datos['metodo_pago']);
            $pedido->modo_entrega = $esRetiroLocal ? 'retiro_local' : 'envio_domicilio';
            $pedido->observaciones = filled($datos['observaciones'] ?? null)
                ? trim((string) $datos['observaciones'])
                : null;
            $pedido->envio = (float) ($checkoutData['costo_envio'] ?? 0);

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

        session()->forget(self::CHECKOUT_SESSION_KEY);

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
        if ($response = $this->bloquearComprasAdmin()) {
            return $response;
        }

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

        $usuario = Usuario::with(['domicilios' => function ($query) {
            $query->where('activo', true)->orderByDesc('es_principal')->latest('id');
        }])->findOrFail($usuarioId);
        $domicilioPrincipal = $this->obtenerDomicilioPrincipalUsuario($usuario);

        return Pedido::create([
            'usuario_id' => $usuario->id,
            'nombre_completo' => trim($usuario->nombre . ' ' . $usuario->apellido),
            'email' => $usuario->email,
            'telefono' => $usuario->telefono,
            'direccion' => $domicilioPrincipal
                ? $this->formatearDomicilioLinea($this->serializarDomicilio($domicilioPrincipal))
                : null,
            'ciudad' => $domicilioPrincipal?->ciudad,
            'provincia' => $domicilioPrincipal?->provincia,
            'codigo_postal' => $domicilioPrincipal?->codigo_postal,
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

    protected function getCheckoutData(): ?array
    {
        $checkoutData = session(self::CHECKOUT_SESSION_KEY);

        return is_array($checkoutData) ? $checkoutData : null;
    }

    protected function enriquecerCheckoutDataConEnvio(?array $checkoutData): ?array
    {
        if (! is_array($checkoutData)) {
            return null;
        }

        $modoEntrega = $checkoutData['modo_entrega'] ?? 'envio';
        $domicilioSnapshot = is_array($checkoutData['domicilio_snapshot'] ?? null)
            ? $checkoutData['domicilio_snapshot']
            : null;

        $checkoutData['provincia_local'] = self::LOCAL_PICKUP_PROVINCE;
        $checkoutData['provincia_entrega'] = $modoEntrega === 'retiro_local'
            ? null
            : trim((string) ($domicilioSnapshot['provincia'] ?? ''));
        $checkoutData['costo_envio'] = $this->calcularCostoEnvioCheckout($modoEntrega, $domicilioSnapshot);

        return $checkoutData;
    }

    protected function serializarDomicilio(Domicilio $domicilio): array
    {
        return [
            'id' => $domicilio->id,
            'calle' => $domicilio->calle,
            'numero' => $domicilio->numero,
            'piso_departamento' => $domicilio->piso_departamento,
            'ciudad' => $domicilio->ciudad,
            'provincia' => $domicilio->provincia,
            'codigo_postal' => $domicilio->codigo_postal,
            'referencia' => $domicilio->referencia,
            'es_principal' => (bool) $domicilio->es_principal,
            'linea_principal' => $this->formatearDomicilioLinea([
                'calle' => $domicilio->calle,
                'numero' => $domicilio->numero,
                'piso_departamento' => $domicilio->piso_departamento,
                'ciudad' => $domicilio->ciudad,
                'provincia' => $domicilio->provincia,
                'codigo_postal' => $domicilio->codigo_postal,
            ]),
        ];
    }

    protected function buildNuevoDomicilioSnapshot(array $domicilio): ?array
    {
        $snapshot = [
            'calle' => trim((string) ($domicilio['calle'] ?? '')),
            'numero' => trim((string) ($domicilio['numero'] ?? '')),
            'piso_departamento' => trim((string) ($domicilio['piso_departamento'] ?? '')),
            'ciudad' => trim((string) ($domicilio['ciudad'] ?? '')),
            'provincia' => trim((string) ($domicilio['provincia'] ?? '')),
            'codigo_postal' => trim((string) ($domicilio['codigo_postal'] ?? '')),
            'referencia' => trim((string) ($domicilio['referencia'] ?? '')),
        ];

        foreach (['calle', 'numero', 'ciudad', 'provincia'] as $field) {
            if ($snapshot[$field] === '') {
                return null;
            }
        }

        $snapshot['linea_principal'] = $this->formatearDomicilioLinea($snapshot);

        return $snapshot;
    }

    protected function buildRetiroLocalSnapshot(): array
    {
        return [
            'tipo' => 'Retiro en local',
            'direccion' => self::LOCAL_PICKUP_ADDRESS,
            'provincia' => self::LOCAL_PICKUP_PROVINCE,
            'linea_principal' => self::LOCAL_PICKUP_ADDRESS,
        ];
    }

    protected function formatearDomicilioLinea(?array $domicilio): ?string
    {
        if (! $domicilio) {
            return null;
        }

        $primeraParte = trim(implode(' ', array_filter([
            $domicilio['calle'] ?? null,
            $domicilio['numero'] ?? null,
        ])));

        $extra = array_filter([
            $domicilio['piso_departamento'] ?? null,
            $domicilio['ciudad'] ?? null,
            $domicilio['provincia'] ?? null,
            $domicilio['codigo_postal'] ?? null,
        ]);

        return trim(implode(', ', array_filter([$primeraParte, implode(', ', $extra)])));
    }

    protected function normalizarProvincia(?string $provincia): string
    {
        return Str::lower(preg_replace('/\s+/', ' ', trim((string) $provincia)));
    }

    protected function calcularCostoEnvioCheckout(string $modoEntrega, ?array $domicilioSnapshot = null): float
    {
        if ($modoEntrega === 'retiro_local') {
            return 0.0;
        }

        $provinciaEntrega = $this->normalizarProvincia($domicilioSnapshot['provincia'] ?? null);

        if ($provinciaEntrega === '') {
            return 0.0;
        }

        return $provinciaEntrega === $this->normalizarProvincia(self::LOCAL_PICKUP_PROVINCE)
            ? self::SHIPPING_SAME_PROVINCE
            : self::SHIPPING_OTHER_PROVINCE;
    }

    protected function aplicarEnvioAlCarrito(array $carrito, float $costoEnvio): array
    {
        $carrito['envio'] = $costoEnvio;
        $carrito['total'] = (float) ($carrito['subtotal'] ?? 0) + $costoEnvio - (float) ($carrito['descuento'] ?? 0);

        return $carrito;
    }

    protected function obtenerDomicilioPrincipalUsuario(Usuario $usuario, $domicilios = null): ?Domicilio
    {
        if ($domicilios instanceof \Illuminate\Database\Eloquent\Collection
            || $domicilios instanceof \Illuminate\Support\Collection) {
            $usuario->setRelation('domicilios', $domicilios);
        }

        return $usuario->domicilioPrincipal();
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

    protected function redirectSiPerfilIncompleto(Usuario $usuario)
    {
        if ($usuario->perfilCheckoutCompleto()) {
            return null;
        }

        return redirect()
            ->route('mis-datos')
            ->with('warning', 'Completa tus datos para continuar.');
    }

    protected function bloquearComprasAdmin(?Usuario $usuario = null, ?Request $request = null)
    {
        $usuarioId = (int) session('usuario_id');

        if (! $usuario && $usuarioId > 0) {
            $usuario = Usuario::find($usuarioId);
        }

        if (! $usuario || $usuario->role !== 'admin') {
            return null;
        }

        $mensaje = 'Los administradores no pueden realizar compras.';

        if ($request?->expectsJson()) {
            return response()->json(['message' => $mensaje], 403);
        }

        return redirect()
            ->route('catalogo')
            ->withErrors(['checkout' => $mensaje]);
    }
}

