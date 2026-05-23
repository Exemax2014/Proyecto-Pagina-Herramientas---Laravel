@extends('layouts.app')

@section('title', 'Mis Datos | Hierro & Forja')

@section('contenido')
<section class="page-section">
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-12 col-lg-9">
                <article class="page-card">
                    @if(session('success'))
                        <div class="alert alert-success mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <strong>Hay errores en el formulario:</strong>

                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                        <div>
                            <span class="home-kicker">Perfil de usuario</span>
                            <h2 class="mb-1">Mis datos</h2>
                            <p class="mb-0 text-muted">
                                Aca podes actualizar la informacion cargada en tu cuenta.
                            </p>
                        </div>

                        @if(session('usuario_role') === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-dark">
                                Volver al panel admin
                            </a>
                        @else
                            <a href="{{ route('catalogo') }}" class="btn btn-outline-dark">
                                Volver al catalogo
                            </a>
                        @endif

                    </div>

                    <form action="{{ route('mis-datos.update') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input
                                        type="text"
                                        id="nombre"
                                        name="nombre"
                                        class="form-control"
                                        value="{{ old('nombre', $usuario->nombre) }}"
                                    >
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <label for="apellido" class="form-label">Apellido</label>
                                    <input
                                        type="text"
                                        id="apellido"
                                        name="apellido"
                                        class="form-control"
                                        value="{{ old('apellido', $usuario->apellido) }}"
                                    >
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <label for="email" class="form-label">Email</label>
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        class="form-control"
                                        value="{{ old('email', $usuario->email) }}"
                                    >
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <label for="dni" class="form-label">DNI</label>
                                    <input
                                        type="text"
                                        id="dni"
                                        name="dni"
                                        class="form-control"
                                        value="{{ old('dni', $usuario->dni) }}"
                                    >
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <label for="telefono" class="form-label">Telefono</label>
                                    <input
                                        type="text"
                                        id="telefono"
                                        name="telefono"
                                        class="form-control"
                                        value="{{ old('telefono', $usuario->telefono) }}"
                                    >
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <label for="direccion" class="form-label">Direccion</label>
                                    <input
                                        type="text"
                                        id="direccion"
                                        name="direccion"
                                        class="form-control"
                                        value="{{ old('direccion', $usuario->direccion) }}"
                                    >
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="border rounded-3 p-3 h-100">
                                    <label for="ciudad" class="form-label">Ciudad</label>
                                    <input
                                        type="text"
                                        id="ciudad"
                                        name="ciudad"
                                        class="form-control"
                                        value="{{ old('ciudad', $usuario->ciudad) }}"
                                    >
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="border rounded-3 p-3 h-100">
                                    <label for="provincia" class="form-label">Provincia</label>
                                    <input
                                        type="text"
                                        id="provincia"
                                        name="provincia"
                                        class="form-control"
                                        value="{{ old('provincia', $usuario->provincia) }}"
                                    >
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="border rounded-3 p-3 h-100">
                                    <label for="codigo_postal" class="form-label">Codigo postal</label>
                                    <input
                                        type="text"
                                        id="codigo_postal"
                                        name="codigo_postal"
                                        class="form-control"
                                        value="{{ old('codigo_postal', $usuario->codigo_postal) }}"
                                    >
                                </div>
                            </div>

                            <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                                @if(session('usuario_role') === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-dark">
                                        Cancelar
                                    </a>
                                @else    
                                    <a href="{{ route('catalogo') }}" class="btn btn-outline-dark">
                                        Cancelar
                                    </a>
                                @endif

                                <button type="submit" class="btn btn-warning">
                                    Guardar cambios
                                </button>
                            </div>
                        </div>
                    </form>
                </article>
            </div>
        </div>

    </div>
</section>
@endsection
