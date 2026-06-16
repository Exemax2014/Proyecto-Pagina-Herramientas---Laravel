@extends('layouts.admin')

@section('title', 'Editar producto | Panel Admin')
@section('page-title', 'Editar producto')

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

    <form action="{{ route('admin.productos.update', $producto) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

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
                                value="{{ old('nombre', $producto->nombre) }}"
                            >
                        </div>

                        <div class="col-12">
                            <label class="admin-form-label">Descripcion</label>
                            <textarea
                                name="descripcion"
                                class="form-control"
                                rows="6"
                            >{{ old('descripcion', $producto->descripcion) }}</textarea>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="admin-form-label">Categoria</label>
                            <select name="categoria_id" class="form-select">
                                <option value="">Seleccionar categoria</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" @selected((string) old('categoria_id', $producto->categoria_id) === (string) $categoria->id)>
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
                                    <option value="{{ $marca->id }}" @selected((string) old('marca_id', $producto->marca_id) === (string) $marca->id)>
                                        {{ $marca->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted d-block mt-2">
                                Si necesitas una marca nueva, cargala primero desde Admin Marcas.
                            </small>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="admin-form-label">Estado</label>
                            <select name="activo" class="form-select">
                                <option value="1" @selected((string) old('activo', (int) $producto->activo) === '1')>Activo</option>
                                <option value="0" @selected((string) old('activo', (int) $producto->activo) === '0')>Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="admin-card mb-2">
                    <h2>Imagenes actuales</h2>

                    <p class="text-muted mb-2">
                        Selecciona cual imagen queres usar como principal del producto.
                    </p>

                    @if($producto->imagenes->count())
                        <div class="admin-main-image-selector">
                            @foreach($producto->imagenes->sortBy('orden') as $imagen)
                                <div class="admin-main-image-option" data-image-card="{{ $imagen->id }}">
                                    <input
                                        type="checkbox"
                                        name="imagenes_eliminar[]"
                                        value="{{ $imagen->id }}"
                                        id="deleteImage{{ $imagen->id }}"
                                        class="admin-delete-image-check d-none"
                                    >

                                    <label class="admin-main-image-radio-label">
                                        <input
                                            type="radio"
                                            name="imagen_principal_id"
                                            value="{{ $imagen->id }}"
                                            class="admin-main-image-radio"
                                            @checked(old('imagen_principal_id', $producto->imagenes->firstWhere('es_principal', true)?->id) == $imagen->id)
                                        >

                                        <div class="admin-main-image-card">
                                            <img src="{{ asset($imagen->url) }}" alt="{{ $producto->nombre }}">
                                            <span class="admin-main-image-check">Principal</span>
                                        </div>
                                    </label>

                                    <button
                                        type="button"
                                        class="admin-old-image-remove"
                                        data-image-id="{{ $imagen->id }}"
                                        title="Eliminar imagen"
                                    >
                                        <i class="bi bi-x"></i>
                                    </button>

                                    <span class="admin-image-delete-label">Se eliminara</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">
                            Este producto todavia no tiene imagenes cargadas.
                        </p>
                    @endif

                    <div class="mb-2 mt-2">
                        <label class="admin-form-label">Subir imagenes</label>

                        <small class="text-muted">
                            Si agregas imagenes nuevas, se sumaran a las actuales.
                        </small>

                        <input
                            type="file"
                            name="imagenes[]"
                            id="productImagesInput"
                            class="form-control"
                            accept="image/*"
                            multiple
                        >

                        <div id="productImagesPreview" class="admin-images-preview mt-3"></div>
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
                            value="{{ old('precio', $producto->precio) }}"
                        >
                    </div>

                    <div class="mb-3">
                        <label class="admin-form-label">Precio anterior</label>
                        <input
                            type="number"
                            name="precio_anterior"
                            class="form-control"
                            placeholder="Opcional. Se puede dejar vacio."
                            value="{{ old('precio_anterior', $producto->precio_anterior) }}"
                        >
                    </div>

                    <div class="mb-3">
                        <label class="admin-form-label">Stock</label>
                        <input
                            type="number"
                            name="stock"
                            class="form-control"
                            value="{{ old('stock', $producto->stock) }}"
                        >
                    </div>

                    <div class="mb-3">
                        <label class="admin-form-label">Ventas registradas</label>
                        <input
                            type="number"
                            name="ventas"
                            class="form-control"
                            min="0"
                            value="{{ old('ventas', $producto->ventas) }}"
                        >
                    </div>
                </div>

                <div class="admin-card mb-4">
                    <h2>Etiqueta</h2>

                    <div class="mb-3">
                        <label class="admin-form-label">Etiqueta manual</label>
                        <select name="etiqueta_id" class="form-select">
                            <option value="">Sin etiqueta manual</option>
                            @foreach($etiquetas as $etiqueta)
                                <option value="{{ $etiqueta->id }}" @selected((string) old('etiqueta_id', $producto->etiqueta_id) === (string) $etiqueta->id)>
                                    {{ $etiqueta->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <small class="text-muted d-block">
                        La oferta se calcula automaticamente cuando el precio anterior es mayor al precio actual. La etiqueta manual es opcional.
                    </small>
                </div>

                <div class="admin-card">
                    <button type="submit" class="btn btn-warning w-100 mb-2">
                        <i class="bi bi-save"></i>
                        Guardar cambios
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
        const oldImageButtons = document.querySelectorAll('.admin-old-image-remove');

        function selectFirstAvailableMainImage() {
            const radios = Array.from(document.querySelectorAll('.admin-main-image-radio'))
                .filter((radio) => !radio.disabled);

            const hasChecked = radios.some((radio) => radio.checked);

            if (!hasChecked && radios.length > 0) {
                radios[0].checked = true;
            }
        }

        oldImageButtons.forEach((button) => {
            button.addEventListener('click', () => {
                const imageId = button.dataset.imageId;
                const card = document.querySelector(`[data-image-card="${imageId}"]`);

                if (!card) return;

                const checkbox = card.querySelector('.admin-delete-image-check');
                const radio = card.querySelector('.admin-main-image-radio');
                const icon = button.querySelector('i');

                checkbox.checked = !checkbox.checked;
                card.classList.toggle('is-marked-delete', checkbox.checked);

                if (checkbox.checked) {
                    radio.disabled = true;

                    if (radio.checked) {
                        radio.checked = false;
                    }

                    icon.className = 'bi bi-arrow-counterclockwise';
                    button.title = 'Cancelar eliminacion';
                } else {
                    radio.disabled = false;
                    icon.className = 'bi bi-x';
                    button.title = 'Eliminar imagen';
                }

                selectFirstAvailableMainImage();
            });
        });

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
