@extends('layouts.app')

@section('title', 'Mis Datos | Hierro & Forja')

@section('contenido')
<section class="page-section">
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-12 col-lg-9">
                <article class="page-card">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                        <div>
                            <span class="home-kicker">Perfil de usuario</span>
                            <h2 class="mb-1">Mis datos</h2>
                            <p class="mb-0 text-muted">
                                Acá podés consultar la información cargada en tu cuenta.
                            </p>
                        </div>

                        <a href="{{ route('catalogo') }}" class="btn btn-outline-dark">
                            Volver al catálogo
                        </a>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <small class="text-muted d-block mb-1">Nombre</small>
                                <strong>{{ $usuario->nombre }}</strong>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <small class="text-muted d-block mb-1">Apellido</small>
                                <strong>{{ $usuario->apellido }}</strong>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <small class="text-muted d-block mb-1">Email</small>
                                <strong>{{ $usuario->email }}</strong>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <small class="text-muted d-block mb-1">DNI</small>
                                <strong>{{ $usuario->dni ?: '-' }}</strong>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <small class="text-muted d-block mb-1">Telefono</small>
                                <strong>{{ $usuario->telefono ?: '-' }}</strong>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <small class="text-muted d-block mb-1">Direccion</small>
                                <strong>{{ $usuario->direccion ?: '-' }}</strong>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="border rounded-3 p-3 h-100">
                                <small class="text-muted d-block mb-1">Ciudad</small>
                                <strong>{{ $usuario->ciudad ?: '-' }}</strong>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="border rounded-3 p-3 h-100">
                                <small class="text-muted d-block mb-1">Provincia</small>
                                <strong>{{ $usuario->provincia ?: '-' }}</strong>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="border rounded-3 p-3 h-100">
                                <small class="text-muted d-block mb-1">Codigo postal</small>
                                <strong>{{ $usuario->codigo_postal ?: '-' }}</strong>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </div>

    </div>
</section>
@endsection
