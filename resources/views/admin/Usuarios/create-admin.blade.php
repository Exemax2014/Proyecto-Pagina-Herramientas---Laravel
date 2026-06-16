@extends('layouts.admin')

@section('title', 'Crear administrador | Panel Admin')
@section('page-title', 'Crear administrador')

@section('contenido')

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Hay errores en el formulario:</strong>

            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.usuarios.store-admin') }}" method="POST">
        @csrf

        <div class="row g-4">
            <div class="col-12 col-xl-8">
                <div class="admin-card mb-4">
                    <h2>Datos del administrador</h2>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="admin-form-label">Nombre</label>
                            <input
                                type="text"
                                name="nombre"
                                class="form-control"
                                value="{{ old('nombre') }}"
                            >
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="admin-form-label">Apellido</label>
                            <input
                                type="text"
                                name="apellido"
                                class="form-control"
                                value="{{ old('apellido') }}"
                            >
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="admin-form-label">Email</label>
                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="{{ old('email') }}"
                            >
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="admin-form-label">Contraseña</label>
                            <input
                                type="password"
                                name="password"
                                class="form-control"
                            >
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="admin-form-label">DNI</label>
                            <input
                                type="text"
                                name="dni"
                                class="form-control"
                                value="{{ old('dni') }}"
                            >
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="admin-form-label">Teléfono</label>
                            <input
                                type="text"
                                name="telefono"
                                class="form-control"
                                value="{{ old('telefono') }}"
                            >
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="admin-form-label">Calle</label>
                            <input
                                type="text"
                                name="calle"
                                class="form-control"
                                value="{{ old('calle') }}"
                            >
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="admin-form-label">Número</label>
                            <input
                                type="text"
                                name="numero"
                                class="form-control"
                                value="{{ old('numero') }}"
                            >
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="admin-form-label">Piso / Departamento</label>
                            <input
                                type="text"
                                name="piso_departamento"
                                class="form-control"
                                value="{{ old('piso_departamento') }}"
                            >
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="admin-form-label">Ciudad</label>
                            <input
                                type="text"
                                name="ciudad"
                                class="form-control"
                                value="{{ old('ciudad') }}"
                            >
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="admin-form-label">Provincia</label>
                            <input
                                type="text"
                                name="provincia"
                                class="form-control"
                                value="{{ old('provincia') }}"
                            >
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="admin-form-label">Código postal</label>
                            <input
                                type="text"
                                name="codigo_postal"
                                class="form-control"
                                value="{{ old('codigo_postal') }}"
                            >
                        </div>

                        <div class="col-12">
                            <label class="admin-form-label">Referencia</label>
                            <input
                                type="text"
                                name="referencia"
                                class="form-control"
                                value="{{ old('referencia') }}"
                            >
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="admin-card">
                    <h2>Acciones</h2>

                    <button type="submit" class="btn btn-warning w-100 mb-2">
                        <i class="bi bi-person-plus"></i>
                        Crear administrador
                    </button>

                    <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-dark w-100">
                        Volver al listado
                    </a>

                </div>
            </div>
        </div>
    </form>

@endsection
