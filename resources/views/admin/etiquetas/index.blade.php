@extends('layouts.admin')

@section('title', 'Etiquetas | Panel Admin')
@section('page-title', 'Etiquetas')

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

    <div class="admin-marcas-stack">
        <div class="admin-card">
            <div class="admin-users-card-head">
                <div class="d-flex">
                    <h2>Agregar nueva etiqueta</h2>
                    <p class="text-muted mb-0 ps-4">
                        Crea etiquetas reutilizables para productos. La oferta se detecta automaticamente por precio anterior.
                    </p>
                </div>
            </div>

            <form action="{{ route('admin.etiquetas.store') }}" method="POST" class="mt-3">
                @csrf

                <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-5">
                        <label class="admin-form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" placeholder="Ej: Nuevo">
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="admin-form-label">Color</label>
                        <input type="color" name="color" class="form-control form-control-color" value="{{ old('color', '#111111') }}">
                    </div>

                    <div class="col-12 col-md-2">
                        <label class="admin-form-label">Activa</label>
                        <select name="activo" class="form-select">
                            <option value="1" @selected(old('activo', '1') === '1')>Si</option>
                            <option value="0" @selected(old('activo') === '0')>No</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-2">
                        <button type="submit" class="btn btn-warning w-100">
                            Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="admin-card p-0 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 admin-marcas-table">
                    <thead class="table-light">
                        <tr>
                            <th>Nombre</th>
                            <th class="text-center">Color</th>
                            <th class="text-center">Vista previa</th>
                            <th class="text-center">Tipo</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Productos asociados</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($etiquetas as $etiqueta)
                            <tr>
                                <td>
                                    <form id="etiqueta-form-{{ $etiqueta->id }}" action="{{ route('admin.etiquetas.update', $etiqueta) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                    </form>

                                    @if($etiqueta->slug === 'oferta')
                                        <input type="hidden" name="nombre" value="{{ $etiqueta->nombre }}" form="etiqueta-form-{{ $etiqueta->id }}">
                                        <input type="text" class="form-control" value="{{ $etiqueta->nombre }}" readonly>
                                    @else
                                        <input type="text" name="nombre" class="form-control" value="{{ $etiqueta->nombre }}" form="etiqueta-form-{{ $etiqueta->id }}">
                                    @endif
                                </td>

                                <td class="text-center">
                                    <input type="color" name="color" class="form-control form-control-color mx-auto" value="{{ $etiqueta->color }}" form="etiqueta-form-{{ $etiqueta->id }}">
                                </td>

                                <td class="text-center">
                                    <div class="product-card-badge-stack product-card-badge-stack--static">
                                        <span class="product-card-badge product-card-badge--static" style="background: {{ $etiqueta->color }}; color: {{ $etiqueta->texto_color }};">
                                            {{ $etiqueta->nombre }}
                                        </span>
                                    </div>
                                </td>

                                <td class="text-center">
                                    @if($etiqueta->slug === 'oferta')
                                        <span class="badge bg-dark">Etiqueta del sistema</span>
                                    @else
                                        <span class="badge bg-light text-dark border">Manual</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <select name="activo" class="form-select" form="etiqueta-form-{{ $etiqueta->id }}">
                                        <option value="1" @selected($etiqueta->activo)>Activa</option>
                                        <option value="0" @selected(! $etiqueta->activo)>Inactiva</option>
                                    </select>
                                </td>

                                <td class="text-center">
                                    <span class="admin-category-count">
                                        <i class="bi bi-box-seam"></i>
                                        {{ $etiqueta->productos_count }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <button type="submit" form="etiqueta-form-{{ $etiqueta->id }}" class="btn btn-outline-primary btn-sm">
                                        Actualizar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    No hay etiquetas cargadas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
