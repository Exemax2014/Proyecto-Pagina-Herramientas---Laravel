@extends('layouts.admin')

@section('title', 'Nuevo producto | Panel Admin')
@section('page-title', 'Nuevo producto')

@section('contenido')

    <form action="#" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">

            <!-- DATOS PRINCIPALES -->
            <div class="col-12 col-xl-8">
                <div class="admin-card mb-4">
                    <h2>Información principal</h2>

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
                            <label class="admin-form-label">Descripción</label>
                            <textarea 
                                name="descripcion" 
                                class="form-control" 
                                rows="6"
                                placeholder="Descripción breve del producto..."
                            >{{ old('descripcion') }}</textarea>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="admin-form-label">Categoría</label>
                            <select name="categoria_id" class="form-select">
                                <option value="">Seleccionar categoría</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}">
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
                                    <option value="{{ $marca->id }}">
                                        {{ $marca->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="admin-form-label">Tipo de energía</label>
                            <select name="energia" class="form-select">
                                <option value="">Seleccionar tipo</option>
                                <option value="electrica">Eléctrica</option>
                                <option value="manual">Manual</option>
                                <option value="inalambrica">Inalámbrica</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="admin-form-label">Estado</label>
                            <select name="activo" class="form-select">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- IMÁGENES -->
                <div class="admin-card">
                    <h2>Imágenes del producto</h2>

                    <div class="mb-4">
                        <label class="admin-form-label">Subir imágenes</label>
                        <input 
                            type="file" 
                            name="imagenes[]" 
                            class="form-control" 
                            accept="image/*"
                            multiple
                        >
                        <small class="text-muted">
                            Podés subir una o varias imágenes. La primera será tomada como imagen principal.
                        </small>
                    </div>
                </div>
            </div>

            <!-- PRECIO Y DATOS COMERCIALES -->
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
                            placeholder="Opcional.  Se puede dejar vacío."
                            value="{{ old('precio_anterior') }}"
                        >
                    </div>

                    <div class="mb-3">
                        <label class="admin-form-label">Stock</label>
                        <input 
                            type="number" 
                            name="stock" 
                            class="form-control" 
                            value="{{ old('stock', 10) }}"
                        >
                    </div>

                </div>

                <div class="admin-card mb-4">
                    <h2>Etiqueta</h2>

                    <div class="mb-3">
                        <label class="admin-form-label">Texto de etiqueta</label>
                        <input 
                            type="text" 
                            name="etiqueta" 
                            class="form-control" 
                            placeholder="Ej: Nuevo, Oferta, Destacado"
                            value="{{ old('etiqueta') }}"
                        >
                    </div>

                    <div class="mb-3">
                        <label class="admin-form-label">Clase de etiqueta</label>
                        <input 
                            type="text" 
                            name="etiqueta_clase" 
                            class="form-control" 
                            placeholder="Opcional.  Se puede dejar vacío."
                            value="{{ old('etiqueta_clase') }}"
                        >
                    </div>
                </div>

                <div class="admin-card">
                    <button type="button" class="btn btn-warning w-100 mb-1" disabled>
                        <i class="bi bi-save"></i>
                        Guardar producto
                    </button>
                </div>
            </div>

        </div>
    </form>

@endsection