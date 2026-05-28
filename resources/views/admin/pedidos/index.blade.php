@extends('layouts.admin')

@section('title', 'Pedidos | Panel Admin')
@section('page-title', 'Pedidos')

@section('contenido')
    @php
        $hayFiltrosActivos = ($buscar !== '') || ($estado !== '');
    @endphp

    <div class="admin-users-stack">
        <div class="admin-pedidos-summary-grid">
            <div class="admin-check-card admin-pedidos-summary-card">
                <div class="stat-icon">
                    <i class="bi bi-bag-check"></i>
                </div>
                <div class="admin-pedidos-summary-copy">
                    <span class="admin-pedidos-summary-label">Pedidos reales</span>
                    <strong class="admin-pedidos-summary-value">{{ $resumen['total_pedidos'] }}</strong>
                </div>
            </div>

            <div class="admin-check-card admin-pedidos-summary-card">
                <div class="stat-icon">
                    <i class="bi bi-patch-check"></i>
                </div>
                <div class="admin-pedidos-summary-copy">
                    <span class="admin-pedidos-summary-label">Confirmados</span>
                    <strong class="admin-pedidos-summary-value">{{ $resumen['confirmados'] }}</strong>
                </div>
            </div>

            <div class="admin-check-card admin-pedidos-summary-card">
                <div class="stat-icon">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="admin-pedidos-summary-copy">
                    <span class="admin-pedidos-summary-label">Preparando</span>
                    <strong class="admin-pedidos-summary-value">{{ $resumen['preparando'] }}</strong>
                </div>
            </div>

            <div class="admin-check-card admin-pedidos-summary-card">
                <div class="stat-icon">
                    <i class="bi bi-truck"></i>
                </div>
                <div class="admin-pedidos-summary-copy">
                    <span class="admin-pedidos-summary-label">Enviados</span>
                    <strong class="admin-pedidos-summary-value">{{ $resumen['enviados'] }}</strong>
                </div>
            </div>

            <div class="admin-check-card admin-pedidos-summary-card">
                <div class="stat-icon">
                    <i class="bi bi-house-check"></i>
                </div>
                <div class="admin-pedidos-summary-copy">
                    <span class="admin-pedidos-summary-label">Entregados</span>
                    <strong class="admin-pedidos-summary-value">{{ $resumen['entregados'] }}</strong>
                </div>
            </div>

            <div class="admin-check-card admin-pedidos-summary-card">
                <div class="stat-icon">
                    <i class="bi bi-cash-coin"></i>
                </div>
                <div class="admin-pedidos-summary-copy">
                    <span class="admin-pedidos-summary-label">Total facturado</span>
                    <strong class="admin-pedidos-summary-value">${{ number_format((float) $resumen['total_facturado'], 0, ',', '.') }}</strong>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <form method="GET" action="{{ route('admin.pedidos.index') }}" class="admin-pedidos-filters">
                <div class="admin-pedidos-filter-state">
                    <select name="estado" id="estado" class="form-select" onchange="this.form.submit()">
                        <option value="">Todos los estados</option>
                        @foreach($estadosPermitidos as $estadoOption)
                            <option value="{{ $estadoOption }}" @selected($estado === $estadoOption)>
                                {{ ucfirst($estadoOption) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="admin-search-block admin-pedidos-search-block">
                    <div class="admin-mini-search">
                        <input
                            type="text"
                            name="buscar"
                            class="form-control"
                            placeholder="Buscar por ID, codigo, cliente, email o telefono..."
                            value="{{ $buscar }}"
                        >

                        <button type="submit" class="btn btn-dark admin-search-btn">
                            <i class="bi bi-search"></i>
                        </button>

                        @if($hayFiltrosActivos)
                            <a href="{{ route('admin.pedidos.index') }}" class="btn btn-outline-secondary admin-clear-btn">
                                Limpiar
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <div class="admin-results-summary">
            @if($buscar !== '' || $estado !== '')
                <span>
                    Resultados filtrados: <strong>{{ $pedidos->total() }}</strong> pedido{{ $pedidos->total() !== 1 ? 's' : '' }}.
                </span>
            @else
                <span>
                    Mostrando <strong>{{ $pedidos->total() }}</strong> pedido{{ $pedidos->total() !== 1 ? 's' : '' }} gestionable{{ $pedidos->total() !== 1 ? 's' : '' }}.
                </span>
            @endif

            @if($estado !== '')
                <span>
                    Estado: <strong>{{ ucfirst($estado) }}</strong>
                </span>
            @endif

            @if($buscar !== '')
                <span>
                    Busqueda: <strong>"{{ $buscar }}"</strong>
                </span>
            @endif
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
                                    No se encontraron pedidos con los filtros aplicados.
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
