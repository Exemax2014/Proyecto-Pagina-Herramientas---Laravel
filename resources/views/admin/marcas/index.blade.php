@extends('layouts.admin')

@section('title', 'Marcas | Panel Admin')
@section('page-title', 'Marcas')

@section('contenido')

    @if ($errors->any())
        <div class="alert alert-danger" data-inline-global-error>
            <strong>Hay errores en el formulario:</strong>

            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="admin-marcas-stack">
        <div class="admin-card">
            <div class="admin-users-card-head">
                <div class="d-flex">
                    <h2 >Agregar nueva marca</h2>
                    <p class="text-muted mb-0 ps-4">
                        Cargá la marca una sola vez acá. Después va a quedar disponible para seleccionarla desde Productos.
                    </p>
                </div>
            </div>

            <form action="{{ route('admin.marcas.store') }}" method="POST" class="mt-3">
                @csrf

                <div class="admin-marcas-create-row">
                    <div class="admin-marcas-create-field">
                        <input
                            type="text"
                            id="nombre_nueva_marca"
                            name="nombre_nueva_marca"
                            class="form-control {{ $errors->has('nombre_nueva_marca') ? 'is-invalid' : '' }}"
                            value="{{ old('nombre_nueva_marca') }}"
                            placeholder="Ej: Bosch"
                        >

                        @if($errors->has('nombre_nueva_marca'))
                            <div class="admin-inline-brand-error">
                                {{ $errors->first('nombre_nueva_marca') }}
                            </div>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-warning admin-marcas-create-btn">
                        <i class="bi bi-plus-circle"></i>
                        Guardar nueva marca
                    </button>
                </div>
            </form>
        </div>

        <div class="admin-card">
            <div class="admin-users-card-head">
                <div>
                    <h2>Marcas visibles en inicio</h2>
                    <p class="text-muted mb-0">
                        Seleccioná exactamente 6, 8 o 12 marcas. El orden final del inicio se define por la posición elegida.
                    </p>
                </div>
            </div>

            <form action="{{ route('admin.marcas.update-home') }}" method="POST" class="mt-3">
                @csrf
                @method('PATCH')

                <div class="row g-3 admin-marcas-home-grid">
                    @foreach(range(1, 12) as $posicion)
                        <div class="col-12 col-md-6 col-xl-3">
                            <label class="admin-form-label">Posición {{ $posicion }}</label>
                            <select name="marcas_inicio[{{ $posicion }}]" class="form-select">
                                <option value="">Seleccionar marca</option>
                                @foreach($marcas as $marca)
                                    <option
                                        value="{{ $marca->id }}"
                                        @selected((string) old("marcas_inicio.$posicion", $marcasInicio[$posicion] ?? '') === (string) $marca->id)
                                    >
                                        {{ $marca->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                </div>

                <div class="admin-marcas-home-actions mt-4">
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save"></i>
                        Guardar selección de inicio
                    </button>
                </div>
            </form>
        </div>

        <div class="admin-card p-0 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 admin-marcas-table">
                    <thead class="table-light">
                        <tr>
                            <th>Marca</th>
                            <th class="text-center">Productos asociados</th>
                            <th class="text-center">Inicio</th>
                            <th class="text-center">Orden</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($marcas as $marca)
                            @php
                                $filaConError = (string) old('_marca_editada') === (string) $marca->id;
                                $valorMarca = $filaConError ? old('nombre') : $marca->nombre;
                            @endphp
                            <tr>
                                <td>
                                    <form action="{{ route('admin.marcas.update', $marca) }}" method="POST" class="admin-inline-form admin-inline-brand-form {{ $filaConError ? 'is-editing' : '' }}" data-inline-form>
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="_marca_editada" value="{{ $marca->id }}">

                                        <div class="admin-inline-brand-row">
                                            <input
                                                type="text"
                                                name="nombre"
                                                class="form-control admin-inline-brand-input {{ $filaConError ? 'is-invalid' : '' }}"
                                                value="{{ $valorMarca }}"
                                                data-inline-input
                                                data-original="{{ $marca->nombre }}"
                                                @disabled(! $filaConError)
                                            >

                                            <button type="button" class="btn btn-sm btn-outline-secondary admin-inline-edit-btn {{ $filaConError ? 'd-none' : '' }}" data-inline-edit>
                                                Editar
                                            </button>

                                            <button type="submit" class="btn btn-sm btn-outline-primary admin-inline-save-btn {{ $filaConError ? '' : 'd-none' }}" data-inline-save>
                                                Guardar
                                            </button>

                                            <button type="button" class="btn btn-sm btn-outline-dark admin-inline-cancel-btn {{ $filaConError ? '' : 'd-none' }}" data-inline-cancel>
                                                Cancelar
                                            </button>
                                        </div>

                                        @if($filaConError && $errors->has('nombre'))
                                            <div class="admin-inline-brand-error" data-inline-error>
                                                {{ $errors->first('nombre') }}
                                            </div>
                                        @endif
                                    </form>
                                </td>

                                <td class="text-center">
                                    <span class="admin-category-count">
                                        <i class="bi bi-box-seam"></i>
                                        {{ $marca->productos_count }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    @if($marca->mostrar_en_inicio)
                                        <span class="badge admin-home-badge admin-home-badge-visible">Visible</span>
                                    @else
                                        <span class="badge admin-home-badge admin-home-badge-hidden">Oculta</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    {{ $marca->orden_inicio ?? '-' }}
                                </td>

                                <td class="text-center">
                                    <span class="admin-marcas-status-note">
                                        Edición inline
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    No hay marcas cargadas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const forms = document.querySelectorAll('[data-inline-form]');

        forms.forEach((form) => {
            const input = form.querySelector('[data-inline-input]');
            const editButton = form.querySelector('[data-inline-edit]');
            const saveButton = form.querySelector('[data-inline-save]');
            const cancelButton = form.querySelector('[data-inline-cancel]');
            const inlineError = form.querySelector('[data-inline-error]');
            const globalError = document.querySelector('[data-inline-global-error]');

            if (!input || !editButton || !saveButton || !cancelButton) return;

            function setEditingState(isEditing) {
                input.disabled = !isEditing;
                form.classList.toggle('is-editing', isEditing);
                editButton.classList.toggle('d-none', isEditing);
                saveButton.classList.toggle('d-none', !isEditing);
                cancelButton.classList.toggle('d-none', !isEditing);
            }

            editButton.addEventListener('click', () => {
                setEditingState(true);
                input.focus();
                input.select();
            });

            cancelButton.addEventListener('click', () => {
                input.value = input.dataset.original ?? '';
                input.classList.remove('is-invalid');
                inlineError?.remove();
                globalError?.remove();
                setEditingState(false);
            });
        });
    });
</script>
@endpush
