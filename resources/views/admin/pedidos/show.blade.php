@extends('layouts.admin')

@section('title', 'Detalle pedido | Panel Admin')
@section('page-title', 'Detalle del pedido')

@section('contenido')
    @php
        $cliente = $pedido->nombre_completo;

        if (! filled($cliente) && $pedido->usuario) {
            $cliente = trim(($pedido->usuario->nombre ?? '') . ' ' . ($pedido->usuario->apellido ?? ''));
        }
    @endphp

    <div class="admin-pedidos-stack">
        <div class="admin-card">
            <div class="admin-pedido-detail-head">
                <div>
                    <h2>Pedido {{ $pedido->codigo ?: '#' . $pedido->id }}</h2>
                    <p class="text-muted mb-0">
                        Revisa los datos del cliente, el detalle de productos comprados y el estado operativo del pedido.
                    </p>
                </div>

                <a href="{{ route('admin.pedidos.index') }}" class="btn btn-outline-dark">
                    Volver al listado
                </a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="admin-card h-100">
                    <div class="admin-pedido-section-head">
                        <h2>Datos generales</h2>
                        <span class="badge admin-status-badge admin-status-{{ $pedido->estado }}">
                            {{ ucfirst($pedido->estado) }}
                        </span>
                    </div>

                    <div class="admin-pedido-detail-grid">
                        <div>
                            <span class="admin-pedido-label">Codigo / ID</span>
                            <strong>{{ $pedido->codigo ?: '#' . $pedido->id }}</strong>
                        </div>
                        <div>
                            <span class="admin-pedido-label">Fecha de confirmacion</span>
                            <strong>{{ $pedido->fecha_confirmacion?->format('d/m/Y H:i') ?? '-' }}</strong>
                        </div>
                        <div>
                            <span class="admin-pedido-label">Cliente</span>
                            <strong>{{ filled($cliente) ? $cliente : 'Cliente sin nombre' }}</strong>
                        </div>
                        <div>
                            <span class="admin-pedido-label">Email</span>
                            <strong>{{ $pedido->email ?: '-' }}</strong>
                        </div>
                        <div>
                            <span class="admin-pedido-label">Telefono</span>
                            <strong>{{ $pedido->telefono ?: '-' }}</strong>
                        </div>
                        <div>
                            <span class="admin-pedido-label">Total</span>
                            <strong>${{ number_format((float) $pedido->total, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="admin-card h-100">
                    <h2>Actualizar estado</h2>
                    <form action="{{ route('admin.pedidos.estado', $pedido) }}" method="POST" class="admin-pedido-status-form">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="estado" class="form-label admin-form-label">Estado</label>
                            <select name="estado" id="estado" class="form-select @error('estado') is-invalid @enderror">
                                @foreach($estadosPermitidos as $estado)
                                    <option value="{{ $estado }}" @selected(old('estado', $pedido->estado) === $estado)>
                                        {{ ucfirst($estado) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-warning w-100">
                            Guardar estado
                        </button>
                    </form>

                    <hr>

                    <div class="admin-pedido-address">
                        <h2 class="mb-3">Entrega y contacto</h2>

                        <div class="admin-pedido-address-line">
                            <span class="admin-pedido-label">Direccion</span>
                            <strong>{{ $pedido->direccion ?: '-' }}</strong>
                        </div>

                        <div class="admin-pedido-address-line">
                            <span class="admin-pedido-label">Ciudad</span>
                            <strong>{{ $pedido->ciudad ?: '-' }}</strong>
                        </div>

                        <div class="admin-pedido-address-line">
                            <span class="admin-pedido-label">Provincia</span>
                            <strong>{{ $pedido->provincia ?: '-' }}</strong>
                        </div>

                        <div class="admin-pedido-address-line">
                            <span class="admin-pedido-label">Codigo postal</span>
                            <strong>{{ $pedido->codigo_postal ?: '-' }}</strong>
                        </div>

                        @if(filled($pedido->observaciones))
                            <div class="admin-pedido-address-line">
                                <span class="admin-pedido-label">Observaciones</span>
                                <strong>{{ $pedido->observaciones }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-card p-0 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 admin-pedidos-table">
                    <thead class="table-light">
                        <tr>
                            <th>Producto</th>
                            <th>Marca</th>
                            <th>Categoria</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-center">Precio unitario</th>
                            <th class="text-center">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pedido->items as $item)
                            <tr>
                                <td>{{ $item->producto_nombre ?: ($item->producto?->nombre ?? 'Producto sin nombre') }}</td>
                                <td>{{ $item->producto_marca ?: ($item->producto?->marca?->nombre ?? 'Sin marca') }}</td>
                                <td>{{ $item->producto_categoria ?: ($item->producto?->categoria?->nombre ?? 'Sin categoria') }}</td>
                                <td class="text-center">{{ $item->cantidad }}</td>
                                <td class="text-center">${{ number_format((float) $item->precio_unitario, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <strong>${{ number_format((float) $item->subtotal, 0, ',', '.') }}</strong>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    Este pedido no tiene items para mostrar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-end">Subtotal</th>
                            <th class="text-center">${{ number_format((float) $pedido->subtotal, 0, ',', '.') }}</th>
                        </tr>
                        <tr>
                            <th colspan="5" class="text-end">Envio</th>
                            <th class="text-center">${{ number_format((float) $pedido->envio, 0, ',', '.') }}</th>
                        </tr>
                        <tr>
                            <th colspan="5" class="text-end">Descuento</th>
                            <th class="text-center">${{ number_format((float) $pedido->descuento, 0, ',', '.') }}</th>
                        </tr>
                        <tr>
                            <th colspan="5" class="text-end">Total</th>
                            <th class="text-center">${{ number_format((float) $pedido->total, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
