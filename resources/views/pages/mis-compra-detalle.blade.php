@extends('layouts.app')

@section('title', 'Detalle de compra | Hierro & Forja')

@section('contenido')
    @php
        $cliente = $pedido->nombre_completo;

        if (! filled($cliente) && $pedido->usuario) {
            $cliente = trim(($pedido->usuario->nombre ?? '') . ' ' . ($pedido->usuario->apellido ?? ''));
        }
    @endphp

    <section class="page-section py-5">
        <div class="container">
            <div class="page-card p-4 p-lg-5">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 mb-4">
                    <div>
                        <span class="home-kicker">Compra {{ $pedido->codigo_visible }}</span>
                        <h1 class="h3 mb-0">Detalle de tu pedido</h1>
                    </div>
                    <div class="text-lg-end">
                        <span class="home-kicker">Estado del Pedido</span>
                        <span class="badge text-bg-light border mb-2">{{ ucfirst($pedido->estado) }}</span>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-lg-8">
                        <div class="border rounded-4 p-5 pt-4 h-100">
                            <h2 class="h5 mb-4">Datos generales</h2>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <span class="d-block text-muted small">Código de pedido</span>
                                    <strong>{{ $pedido->codigo_visible }}</strong>
                                </div>
                                <div class="col-md-6">
                                    <span class="d-block text-muted small">Cliente</span>
                                    <strong>{{ filled($cliente) ? $cliente : 'Cliente sin nombre' }}</strong>
                                </div>
                                <div class="col-md-6">
                                    <span class="d-block text-muted small">Fecha de confirmación</span>
                                    <strong>{{ $pedido->fecha_confirmacion?->format('d/m/Y H:i') ?? '-' }}</strong>
                                </div>
                                <div class="col-md-6">
                                    <span class="d-block text-muted small">Método de pago</span>
                                    <strong>{{ $pedido->metodo_pago ? ucfirst($pedido->metodo_pago) : '-' }}</strong>
                                </div>
                                <div class="col-md-6">
                                    <span class="d-block text-muted small">Email</span>
                                    <strong>{{ $pedido->email ?: '-' }}</strong>
                                </div>
                                <div class="col-md-6">
                                    <span class="d-block text-muted small">Teléfono</span>
                                    <strong>{{ $pedido->telefono ?: '-' }}</strong>
                                </div>
                            </div>

                            <h3 class="h6 mb-3">Línea de tiempo</h3>
                            <div class="d-flex flex-row flex-nowrap gap-3 overflow-auto">
                                @foreach($lineaEstados as $estadoPaso)
                                    <div class="d-flex align-items-start gap-3 flex-shrink-0">
                                        <span class="rounded-circle flex-shrink-0 {{ $estadoPaso['completado'] ? 'bg-warning' : 'bg-secondary-subtle' }}" style="width: 14px; height: 14px; margin-top: 6px;"></span>
                                        <div>
                                            <strong class="d-block">{{ $estadoPaso['titulo'] }}</strong>
                                            <span class="text-muted small">
                                                {{ $estadoPaso['fecha']?->format('d/m/Y H:i') ?? 'Pendiente' }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="border rounded-4 p-4 h-100">
                            <h2 class="h5 mb-4">Entrega</h2>

                            <div class="mb-3">
                                <span class="d-block text-muted small">Dirección</span>
                                <strong>{{ $pedido->direccion ?: '-' }}</strong>
                            </div>

                            <div class="mb-3">
                                <span class="d-block text-muted small">Ciudad</span>
                                <strong>{{ $pedido->ciudad ?: '-' }}</strong>
                            </div>

                            <div class="mb-3">
                                <span class="d-block text-muted small">Provincia</span>
                                <strong>{{ $pedido->provincia ?: '-' }}</strong>
                            </div>

                            <div class="mb-3">
                                <span class="d-block text-muted small">Código postal</span>
                                <strong>{{ $pedido->codigo_postal ?: '-' }}</strong>
                            </div>

                            @if(filled($pedido->observaciones))
                                <div>
                                    <span class="d-block text-muted small">Observaciones</span>
                                    <strong>{{ $pedido->observaciones }}</strong>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="border rounded-4 p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                        <h2 class="h5 mb-0">Productos del pedido</h2>
                        <a href="{{ route('mis-compras.index') }}" class="btn btn-outline-dark">Volver a mis compras</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Producto</th>
                                    <th>Marca</th>
                                    <th>Categoría</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-end">Precio unitario</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pedido->items as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->producto_nombre ?: ($item->producto?->nombre ?? 'Producto sin nombre') }}</strong>
                                        </td>
                                        <td>{{ $item->producto_marca ?: ($item->producto?->marca?->nombre ?? 'Sin marca') }}</td>
                                        <td>{{ $item->producto_categoria ?: ($item->producto?->categoria?->nombre ?? 'Sin categoría') }}</td>
                                        <td class="text-center">{{ $item->cantidad }}</td>
                                        <td class="text-end">${{ number_format((float) $item->precio_unitario, 0, ',', '.') }}</td>
                                        <td class="text-end"><strong>${{ number_format((float) $item->subtotal, 0, ',', '.') }}</strong></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">Este pedido no tiene productos para mostrar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-end">Subtotal</th>
                                    <th class="text-end">${{ number_format((float) $pedido->subtotal, 0, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="5" class="text-end">Envío</th>
                                    <th class="text-end">${{ number_format((float) $pedido->envio, 0, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="5" class="text-end">Descuento</th>
                                    <th class="text-end">${{ number_format((float) $pedido->descuento, 0, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="5" class="text-end">Total</th>
                                    <th class="text-end">${{ number_format((float) $pedido->total, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
