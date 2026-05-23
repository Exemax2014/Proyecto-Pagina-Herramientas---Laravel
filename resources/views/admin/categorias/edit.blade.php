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
                <div class="admin-card admin-category-form-card h-100">
                    <h2>Datos de la categoria</h2>

                    <div class="row g-4 admin-category-form-grid">
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
                            <div class="admin-check-card">
                                <input
                                    class="form-check-input admin-check-card-input"
                                    type="checkbox"
                                    name="mostrar_en_inicio"
                                    id="mostrarEnInicio"
                                    value="1"
                                    @checked(old('mostrar_en_inicio', $categoria->mostrar_en_inicio))
                                >
                                <label class="form-check-label admin-check-card-label" for="mostrarEnInicio">
                                    <span class="admin-check-card-title">Mostrar en inicio</span>
                                    <span class="admin-check-card-copy">Solo disponible para categorias con productos asociados.</span>
                                </label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="admin-form-label">Orden en inicio</label>
                            <select
                                name="orden_inicio"
                                class="form-select"
                            >
                                <option value="">Seleccionar orden</option>
                                @foreach(range(1, 6) as $orden)
                                    <option value="{{ $orden }}" @selected((string) old('orden_inicio', $categoria->orden_inicio) === (string) $orden)>
                                        {{ $orden }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4 admin-category-side-stack">
                <div class="admin-card mb-4">
                    <h2>Imagen actual</h2>

                    @if($categoria->imagen_url)
                        <div class="admin-category-image-panel">
                            <img
                                src="{{ asset($categoria->imagen_url) }}"
                                alt="{{ $categoria->nombre }}"
                                class="admin-category-image-current"
                            >
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const mostrarEnInicio = document.getElementById('mostrarEnInicio');
        const ordenInput = document.querySelector('select[name="orden_inicio"]');

        if (!mostrarEnInicio || !ordenInput) return;

        function syncOrdenInicio() {
            const activa = mostrarEnInicio.checked;

            ordenInput.disabled = !activa;
            ordenInput.required = activa;

            if (!activa) {
                ordenInput.value = '';
            }
        }

        mostrarEnInicio.addEventListener('change', syncOrdenInicio);
        syncOrdenInicio();
    });
</script>
@endpush
