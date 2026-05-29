@extends('layouts.app')

@section('title', 'Mis compras | Hierro & Forja')

@section('contenido')
<section class="page-section py-5">
    <div class="container">
        <div class="cart-hero mb-4">
            <div class="cart-hero-inline">
                <span class="home-kicker">Cuenta</span>
                <p class="cart-hero-copy">Revisá el historial de tus pedidos confirmados y seguí el estado de cada compra.</p>
            </div>
        </div>

        <div class="page-card p-4 p-lg-5">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                <div>
                    <h1 class="h3 mb-1">Mis compras</h1>
                    <p class="text-muted mb-0">Acá vas a encontrar solo los pedidos asociados a tu cuenta.</p>
                </div>
                <a href="{{ route('catalogo') }}" class="btn btn-outline-dark">Seguir comprando</a>
            </div>

            @if($pedidos->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-bag-x fs-1 d-block mb-3 text-muted"></i>
                    <h2 class="h4 mb-2">Todavía no tenés compras confirmadas</h2>
                    <p class="text-muted mb-4">Cuando confirmes un pedido, lo vas a poder consultar desde esta sección.</p>
                    <a href="{{ route('catalogo') }}" class="btn btn-warning">Ir al catálogo</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Pedido</th>
                                <th>Fecha de confirmación</th>
                                <th>Estado</th>
                                <th class="text-md-center">Productos</th>
                                <th class="text-md-end">Total</th>
                                <th class="text-md-end">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pedidos as $pedido)
                                <tr>
                                    <td>
                                        <strong>{{ $pedido->codigo_visible }}</strong>
                                    </td>
                                    <td>{{ $pedido->fecha_confirmacion?->format('d/m/Y H:i') ?? '-' }}</td>
                                    <td>
                                        <span class="badge text-bg-light border">{{ ucfirst($pedido->estado) }}</span>
                                    </td>
                                    <td class="text-md-center">{{ $pedido->items_count }}</td>
                                    <td class="text-md-end">
                                        <strong>${{ number_format((float) $pedido->total, 0, ',', '.') }}</strong>
                                    </td>
                                    <td class="text-md-end">
                                        <a href="{{ route('mis-compras.show', $pedido) }}" class="btn btn-outline-dark btn-sm">
                                            Ver detalle
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $pedidos->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
