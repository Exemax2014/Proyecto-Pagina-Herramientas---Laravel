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

    <form action="{{ route('admin.categorias.update', $categoria) }}" method="POST">
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
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="admin-card mb-4">
                    <h2>Uso actual</h2>

                    <div class="admin-category-usage">
                        <span class="admin-category-count">
                            <i class="bi bi-box-seam"></i>
                            {{ $categoria->productos_count }} producto{{ $categoria->productos_count === 1 ? '' : 's' }}
                        </span>
                    </div>
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
