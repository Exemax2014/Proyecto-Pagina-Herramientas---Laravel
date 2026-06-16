@extends('layouts.admin')

@section('title', 'Nuevo producto | Panel Admin')
@section('page-title', 'Nuevo producto')

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

    <form action="{{ route('admin.productos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
            <div class="col-12 col-xl-8">
                <div class="admin-card mb-4">
                    <h2>Informacion principal</h2>

                    <div class="row g-2">
                        <div class="col-12">
                            <label class="admin-form-label">Nombre del producto</label>
                            <input
                                type="text"
                                name="nombre"
                                class="form-control"
                                value="{{ old('nombre') }}"
                            >
                        </div>

                        <div class="col-12">
                            <label class="admin-form-label">Descripcion</label>
                            <textarea
                                name="descripcion"
                                class="form-control"
                                rows="6"
                                placeholder="Descripcion breve del producto..."
                            >{{ old('descripcion') }}</textarea>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="admin-form-label">Categoria</label>
                            <select name="categoria_id" class="form-select">
                                <option value="">Seleccionar categoria</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" @selected((string) old('categoria_id') === (string) $categoria->id)>
                                        {{ $categoria->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="admin-form-label">Marca</label>
                            <select name="marca_id" class="form-select">
                                <option value="">Seleccionar marca</option>
                                @foreach($marcas as $marca)
                                    <option value="{{ $marca->id }}" @selected((string) old('marca_id') === (string) $marca->id)>
                                        {{ $marca->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted d-block mt-2">
                                Si necesitas una marca nueva, cargala primero desde Admin Marcas.
                            </small>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="admin-form-label">Tipo de energia</label>
                            <select name="energia" class="form-select">
                                <option value="">Seleccionar tipo</option>
                                <option value="electrica" @selected(old('energia') === 'electrica')>Electrica</option>
                                <option value="manual" @selected(old('energia') === 'manual')>Manual</option>
                                <option value="inalambrica" @selected(old('energia') === 'inalambrica')>Inalambrica</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="admin-card">
                    <h2>Imagenes del producto</h2>

                    <div class="mb-4">
                        <label class="admin-form-label">Subir imagenes</label>
                        <input
                            type="file"
                            name="imagenes[]"
                            id="productImagesInput"
                            class="form-control"
                            accept="image/*"
                            multiple
                        >
                        <div id="productImagesPreview" class="admin-images-preview mt-3"></div>

                        <small class="text-muted">
                            Podes subir una o varias imagenes. La primera sera tomada como imagen principal.
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="admin-card mb-4">
                    <h2>Precio y stock</h2>

                    <div class="mb-3">
                        <label class="admin-form-label">Precio actual</label>
                        <input
                            type="number"
                            name="precio"
                            class="form-control"
                            value="{{ old('precio') }}"
                        >
                    </div>

                    <div class="mb-3">
                        <label class="admin-form-label">Precio anterior</label>
                        <input
                            type="number"
                            name="precio_anterior"
                            class="form-control"
                            placeholder="Opcional. Se puede dejar vacio."
                            value="{{ old('precio_anterior') }}"
                        >
                    </div>

                    <div class="mb-3">
                        <label class="admin-form-label">Stock</label>
                        <input
                            type="number"
                            name="stock"
                            class="form-control"
                            value="{{ old('stock') }}"
                        >
                    </div>
                </div>

                <div class="admin-card mb-4">
                    <h2>Etiqueta</h2>

                    <div class="mb-3">
                        <label class="admin-form-label">Etiqueta manual</label>
                        <select name="etiqueta_id" class="form-select">
                            <option value="">Sin etiqueta</option>
                            @foreach($etiquetas as $etiqueta)
                                <option value="{{ $etiqueta->id }}" @selected((string) old('etiqueta_id') === (string) $etiqueta->id)>
                                    {{ $etiqueta->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <small class="text-muted d-block">
                        Las etiquetas se administran desde el Panel.
                    </small>
                    <small class="text-muted d-block">
                        Si el producto tiene un precio anterior mayor al actual, la etiqueta oferta y el descuento se muestran automaticamente.
                    </small>
                </div>

                <div class="admin-card">
                    <button type="submit" class="btn btn-warning w-100 mb-1">
                        <i class="bi bi-save"></i>
                        Guardar producto
                    </button>

                    <a href="{{ route('admin.productos.index') }}" class="btn btn-outline-dark w-100">
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
        const input = document.getElementById('productImagesInput');
        const preview = document.getElementById('productImagesPreview');

        if (!input || !preview) return;

        let selectedFiles = [];

        input.addEventListener('change', () => {
            selectedFiles = Array.from(input.files);
            renderPreview();
            updateInputFiles();
        });

        function renderPreview() {
            preview.innerHTML = '';

            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();

                reader.onload = (event) => {
                    const item = document.createElement('div');
                    item.classList.add('admin-image-preview-item');

                    item.innerHTML = `
                        <img src="${event.target.result}" alt="Imagen ${index + 1}">
                        <button type="button" class="admin-image-remove" data-index="${index}">
                            <i class="bi bi-x"></i>
                        </button>
                        ${index === 0 ? '<span class="admin-image-main">Principal</span>' : ''}
                    `;

                    preview.appendChild(item);
                };

                reader.readAsDataURL(file);
            });
        }

        preview.addEventListener('click', (event) => {
            const removeButton = event.target.closest('.admin-image-remove');

            if (!removeButton) return;

            const index = Number(removeButton.dataset.index);
            selectedFiles.splice(index, 1);
            renderPreview();
            updateInputFiles();
        });

        function updateInputFiles() {
            const dataTransfer = new DataTransfer();

            selectedFiles.forEach((file) => {
                dataTransfer.items.add(file);
            });

            input.files = dataTransfer.files;
        }
    });
</script>
@endpush
