@extends('layouts.admin')

@section('title', 'Nueva categoria | Panel Admin')
@section('page-title', 'Nueva categoria')

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

    <form action="{{ route('admin.categorias.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
            <div class="col-12 col-xl-8">
                <div class="admin-card">
                    <h2>Datos de la categoria</h2>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="admin-form-label">Nombre</label>
                            <input
                                type="text"
                                name="nombre"
                                class="form-control"
                                value="{{ old('nombre') }}"
                                placeholder="Ej: Ferreteria"
                            >
                        </div>

                        <div class="col-12">
                            <label class="admin-form-label">Slug</label>
                            <input
                                type="text"
                                name="slug"
                                class="form-control"
                                value="{{ old('slug') }}"
                                placeholder="Opcional. Si lo dejas vacio, se genera automaticamente."
                            >

                            <small class="text-muted">
                                Se normaliza automaticamente para usarlo en filtros, enlaces y catalogo.
                            </small>
                        </div>

                        <div class="col-12">
                            <label class="admin-form-label">Imagen de la categoria</label>
                            <input
                                type="file"
                                name="imagen"
                                class="form-control"
                                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            >

                            <small class="text-muted">
                                Formatos permitidos: JPG, JPEG, PNG y WEBP. Maximo 4096 KB.
                            </small>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-check admin-check-block">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="mostrar_en_inicio"
                                    id="mostrarEnInicio"
                                    value="1"
                                    @checked(old('mostrar_en_inicio', true))
                                >
                                <label class="form-check-label" for="mostrarEnInicio">
                                    Mostrar en inicio
                                </label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="admin-form-label">Orden en inicio</label>
                            <input
                                type="number"
                                name="orden_inicio"
                                class="form-control"
                                min="1"
                                value="{{ old('orden_inicio') }}"
                                placeholder="Opcional"
                            >
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="admin-card">
                    <h2>Acciones</h2>

                    <button type="submit" class="btn btn-warning w-100 mb-2">
                        <i class="bi bi-save"></i>
                        Guardar categoria
                    </button>

                    <a href="{{ route('admin.categorias.index') }}" class="btn btn-outline-dark w-100">
                        Volver al listado
                    </a>
                </div>
            </div>
        </div>
    </form>

@endsection
