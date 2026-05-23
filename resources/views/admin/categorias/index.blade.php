@extends('layouts.admin')

@section('title', 'Categorias | Panel Admin')
@section('page-title', 'Categorias')

@section('contenido')

    <div class="admin-card mb-4">
        <div class="admin-users-card-head">
            <div>
                <h2>Gestion de categorias</h2>
                <p class="text-muted mb-0">
                    Administra las categorias que usa el catalogo y los formularios de productos.
                </p>
            </div>

            <a href="{{ route('admin.categorias.create') }}" class="btn btn-warning">
                <i class="bi bi-tags"></i>
                Nueva categoria
            </a>
        </div>
    </div>

    <div class="admin-card p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 admin-categories-table">
                <thead class="table-light">
                    <tr>
                        <th>Categoria</th>
                        <th>Slug</th>
                        <th class="text-center">Productos asociados</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($categorias as $categoria)
                        <tr>
                            <td>
                                <strong>{{ $categoria->nombre }}</strong>
                            </td>

                            <td>
                                <code>{{ $categoria->slug }}</code>
                            </td>

                            <td class="text-center">
                                <span class="admin-category-count">
                                    <i class="bi bi-box-seam"></i>
                                    {{ $categoria->productos_count }}
                                </span>
                            </td>

                            <td class="text-center">
                                <a href="{{ route('admin.categorias.edit', $categoria) }}" class="btn btn-sm btn-outline-primary admin-action-btn">
                                    Editar
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                No hay categorias cargadas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="admin-pagination-wrapper mt-4">
        {{ $categorias->links('pagination::bootstrap-5') }}
    </div>

@endsection
