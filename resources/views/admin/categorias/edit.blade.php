@extends('layouts.admin')

@section('title', 'Editar categoria | Panel Admin')
@section('page-title', 'Editar categoria')

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

    <form action="{{ route('admin.categorias.update', $categoria) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="row g-4">
            <div class="col-12 col-xl-8">
                <div class="admin-card mb-4">
                    <h2>Datos de la categoria</h2>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="admin-form-label">Nombre</label>
                            <input
                                type="text"
                                name="nombre"
                                class="form-control"
                                value="{{ old('nombre', $categoria->nombre) }}"
                            >
                        </div>

                        <div class="col-12">
                            <label class="admin-form-label">Slug</label>
                            <input
                                type="text"
                                name="slug"
                                class="form-control"
                                value="{{ old('slug', $categoria->slug) }}"
                            >

                            <small class="text-muted">
                                Si lo modificas, se volvera a normalizar automaticamente.
                            </small>
                        </div>

                        <div class="col-12">
                            <label class="admin-form-label">Imagen nueva</label>
                            <input
                                type="file"
                                name="imagen"
                                class="form-control"
                                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            >

                            <small class="text-muted">
                                Si subis una nueva imagen, reemplaza la actual.
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
                                    @checked(old('mostrar_en_inicio', $categoria->mostrar_en_inicio))
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
                                value="{{ old('orden_inicio', $categoria->orden_inicio) }}"
                                placeholder="Opcional"
                            >
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="admin-card mb-4">
                    <h2>Imagen actual</h2>

                    @if($categoria->imagen_url)
                        <div class="admin-category-image-panel">
                            <img
                                src="{{ asset($categoria->imagen_url) }}"
                                alt="{{ $categoria->nombre }}"
                                class="admin-category-image-current"
                            >

                            <div class="form-check admin-check-block mt-3">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="eliminar_imagen"
                                    id="eliminarImagen"
                                    value="1"
                                    @checked(old('eliminar_imagen'))
                                >
                                <label class="form-check-label" for="eliminarImagen">
                                    Eliminar imagen actual
                                </label>
                            </div>
                        </div>
                    @else
                        <div class="admin-category-image-panel admin-category-image-empty">
                            <i class="bi bi-image"></i>
                            <span>Sin imagen cargada</span>
                        </div>
                    @endif
                </div>

                <div class="admin-card">
                    <h2>Acciones</h2>

                    <button type="submit" class="btn btn-warning w-100 mb-2">
                        <i class="bi bi-save"></i>
                        Guardar cambios
                    </button>

                    <a href="{{ route('admin.categorias.index') }}" class="btn btn-outline-dark w-100">
                        Volver al listado
                    </a>
                </div>
            </div>
        </div>
    </form>

@endsection
