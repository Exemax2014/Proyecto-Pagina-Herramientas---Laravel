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
                <div class="admin-card admin-category-form-card h-100">
                    <h2>Datos de la categoria</h2>

                    <div class="row g-4 admin-category-form-grid">
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
                            <div class="admin-check-card">
                                <input
                                    class="form-check-input admin-check-card-input"
                                    type="checkbox"
                                    name="mostrar_en_inicio"
                                    id="mostrarEnInicio"
                                    value="1"
                                    @checked(old('mostrar_en_inicio'))
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
                                    <option value="{{ $orden }}" @selected((string) old('orden_inicio') === (string) $orden)>
                                        {{ $orden }}
                                    </option>
                                @endforeach
                            </select>
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
