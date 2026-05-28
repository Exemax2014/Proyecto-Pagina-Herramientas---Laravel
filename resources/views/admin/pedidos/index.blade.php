@extends('layouts.admin')

@section('title', 'Pedidos | Panel Admin')
@section('page-title', 'Pedidos')

@section('contenido')
    <div class="admin-users-stack">
        <div class="admin-card">
            <div class="admin-users-card-head">
                <div>
                    <h2>Pedidos confirmados y en gestion</h2>
                    <p class="text-muted mb-0">
                        Se muestran solo pedidos reales. Los carritos activos quedan fuera de este modulo hasta que el cliente confirma la compra.
                    </p>
                </div>
            </div>
        </div>

        <div class="admin-results-summary">
            <span>
                Mostrando <strong>{{ $pedidos->total() }}</strong> pedido{{ $pedidos->total() !== 1 ? 's' : '' }} gestionable{{ $pedidos->total() !== 1 ? 's' : '' }}.
            </span>
        </div>

        <div class="admin-card p-0 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 admin-pedidos-table">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">ID</th>
                            <th class="text-center">Fecha</th>
                            <th>Cliente</th>
                            <th>Email</th>
                            <th>Telefono</th>
                            <th class="text-center">Items</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Accion</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($pedidos as $pedido)
                            @php
                                $cliente = $pedido->nombre_completo;

                                if (! filled($cliente) && $pedido->usuario) {
                                    $cliente = trim(($pedido->usuario->nombre ?? '') . ' ' . ($pedido->usuario->apellido ?? ''));
                                }
                            @endphp
                            <tr>
                                <td class="text-center">
                                    <strong>{{ $pedido->codigo ?: '#' . $pedido->id }}</strong>
                                </td>
                                <td class="text-center">
                                    {{ $pedido->fecha_confirmacion?->format('d/m/Y H:i') ?? '-' }}
                                </td>
                                <td>
                                    {{ filled($cliente) ? $cliente : 'Cliente sin nombre' }}
                                </td>
                                <td>{{ $pedido->email ?: '-' }}</td>
                                <td>{{ $pedido->telefono ?: '-' }}</td>
                                <td class="text-center">
                                    {{ $pedido->items_count }}
                                </td>
                                <td class="text-center">
                                    <strong>${{ number_format((float) $pedido->total, 0, ',', '.') }}</strong>
                                </td>
                                <td class="text-center">
                                    <span class="badge admin-status-badge admin-status-{{ $pedido->estado }}">
                                        {{ ucfirst($pedido->estado) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.pedidos.show', $pedido) }}" class="btn btn-sm btn-outline-primary admin-action-btn">
                                        Ver detalle
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    No hay pedidos confirmados o en gestion para mostrar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($pedidos->hasPages())
            <div class="admin-card">
                <div class="admin-pagination-wrapper">
                    {{ $pedidos->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
@endsection
