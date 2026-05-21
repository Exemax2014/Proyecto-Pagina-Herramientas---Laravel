@extends('layouts.admin')

@section('title', 'Usuarios | Panel Admin')
@section('page-title', 'Usuarios')

@section('contenido')

    <div class="admin-users-stack">

        {{-- ADMINISTRADORES --}}
        <div class="admin-card">
            <a href="{{ route('admin.usuarios.create-admin') }}" class="btn btn-sm btn-warning">
                <i class="bi bi-person-plus"></i>
                Crear administrador
            </a>

            <form method="GET" action="{{ route('admin.usuarios.index') }}" class="admin-mini-search mt-3 mb-3">
                <input 
                    type="text"
                    name="buscar_admin"
                    class="form-control"
                    placeholder="Buscar administrador por nombre, email o DNI..."
                    value="{{ $buscarAdmin }}"
                >

                <button type="submit" class="btn btn-dark">
                    <i class="bi bi-search"></i>
                </button>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 admin-users-table">
                    <thead class="table-light">
                        <tr>
                            <th>Administrador</th>
                            <th>Email</th>
                            <th>DNI</th>
                            <th>Teléfono</th>
                            <th>Ciudad</th>
                            <th>Estado</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($administradores as $usuario)
                            <tr>
                                <td>
                                    <strong>{{ $usuario->nombre }} {{ $usuario->apellido }}</strong>
                                </td>

                                <td>{{ $usuario->email }}</td>

                                <td>{{ $usuario->dni ?? '-' }}</td>

                                <td>{{ $usuario->telefono ?? '-' }}</td>

                                <td>
                                    {{ $usuario->ciudad ?? '-' }}
                                    @if($usuario->provincia)
                                        <div class="small text-muted">
                                            {{ $usuario->provincia }}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    @if($usuario->activo)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-secondary">Inactivo</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if($usuario->email === 'admin@hierroforja.com')
                                        <button type="button" class="btn btn-sm btn-outline-dark admin-user-action-btn" disabled>
                                            Root
                                        </button>
                                    @elseif($usuario->activo)
                                        <form 
                                            action="{{ route('admin.usuarios.desactivar', $usuario) }}" 
                                            method="POST"
                                            class="admin-action-form"
                                            onsubmit="return confirm('¿Seguro que querés dar de baja este administrador?');"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit" class="btn btn-sm btn-outline-danger admin-user-action-btn">
                                                Dar de baja
                                            </button>
                                        </form>
                                    @else
                                        <form 
                                            action="{{ route('admin.usuarios.activar', $usuario) }}" 
                                            method="POST"
                                            class="admin-action-form"
                                            onsubmit="return confirm('¿Querés dar de alta este administrador?');"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit" class="btn btn-sm btn-outline-success admin-user-action-btn">
                                                Dar de alta
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    No se encontraron administradores.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="admin-pagination-wrapper mt-3">
                {{ $administradores->links('pagination::bootstrap-5') }}
            </div>
        </div>


        {{-- COMPRADORES --}}
        <div class="admin-card">
            <div class="admin-users-card-head">
                <h2>Usuarios Clientes</h2>
            </div>

            <form method="GET" action="{{ route('admin.usuarios.index') }}" class="admin-mini-search mt-3 mb-3">
                <input 
                    type="text"
                    name="buscar_comprador"
                    class="form-control"
                    placeholder="Buscar comprador por nombre, email o DNI..."
                    value="{{ $buscarComprador }}"
                >

                <button type="submit" class="btn btn-dark">
                    <i class="bi bi-search"></i>
                </button>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 admin-users-table admin-buyers-table">
                    <thead class="table-light">
                        <tr>
                            <th>Cliente</th>
                            <th>Email</th>
                            <th>DNI</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Ciudad / Provincia</th>
                            <th>C.P.</th>
                            <th>Estado</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($compradores as $usuario)
                            <tr>
                                <td>
                                    <strong>{{ $usuario->nombre }} {{ $usuario->apellido }}</strong>

                                    <div class="small text-muted">
                                        ID: {{ $usuario->id }}
                                    </div>
                                </td>

                                <td>{{ $usuario->email }}</td>

                                <td>{{ $usuario->dni ?? '-' }}</td>

                                <td>{{ $usuario->telefono ?? '-' }}</td>

                                <td>{{ $usuario->direccion ?? '-' }}</td>

                                <td>
                                    {{ $usuario->ciudad ?? '-' }}

                                    @if($usuario->provincia)
                                        <div class="small text-muted">
                                            {{ $usuario->provincia }}
                                        </div>
                                    @endif
                                </td>

                                <td>{{ $usuario->codigo_postal ?? '-' }}</td>

                                <td>
                                    @if($usuario->activo)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-secondary">Inactivo</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if($usuario->activo)
                                        <form 
                                            action="{{ route('admin.usuarios.desactivar', $usuario) }}" 
                                            method="POST"
                                            class="admin-action-form"
                                            onsubmit="return confirm('¿Seguro que querés dar de baja este usuario?');"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit" class="btn btn-sm btn-outline-danger admin-user-action-btn">
                                                Dar de baja
                                            </button>
                                        </form>
                                    @else
                                        <form 
                                            action="{{ route('admin.usuarios.activar', $usuario) }}" 
                                            method="POST"
                                            class="admin-action-form"
                                            onsubmit="return confirm('¿Querés dar de alta este usuario?');"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit" class="btn btn-sm btn-outline-success admin-user-action-btn">
                                                Dar de alta
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    No se encontraron compradores.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="admin-pagination-wrapper mt-3">
                {{ $compradores->links('pagination::bootstrap-5') }}
            </div>
        </div>

    </div>

@endsection