@extends('layouts.app')

@section('contenido')

<div class="container mt-5">

    <h1 class="mb-5 fw-bold">Pagos y Envíos</h1>

    <div class="row">

        <!-- PAGOS -->
        <div class="col-md-6">
            <div class="card p-4 shadow-sm mb-4">
                <h3>💳 Medios de Pago</h3>
                <ul>
                    <li>Efectivo</li>
                    <li>Transferencia bancaria</li>
                    <li>Tarjetas de débito/crédito</li>
                    <li>Mercado Pago</li>
                </ul>
            </div>
        </div>

        <!-- ENVÍOS -->
        <div class="col-md-6">
            <div class="card p-4 shadow-sm mb-4">
                <h3>🚚 Formas de Envío</h3>
                <ul>
                    <li>Retiro en local</li>
                    <li>Envíos a domicilio</li>
                    <li>Envíos por correo</li>
                </ul>
            </div>
        </div>

    </div>

    <!-- HORARIOS -->
    <div class="card p-4 shadow-sm mt-3">
        <h3>🕒 Horarios de atención</h3>
        <p>Lunes a Viernes: 8:00 a 18:00 hs</p>
        <p>Sábados: 8:00 a 13:00 hs</p>
    </div>

    <!-- BOTÓN VOLVER -->
    <a href="/comercializacion" class="btn btn-dark mt-4">
        ← Volver
    </a>

</div>

@endsection