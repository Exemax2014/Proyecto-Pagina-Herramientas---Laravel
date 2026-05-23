@extends('layouts.admin')

@section('title', 'Consultas | Panel Admin')
@section('page-title', 'Consultas')

@section('contenido')
    <div class="admin-users-stack">
        <div class="admin-card">
            <div class="admin-users-card-head">
                <div>
                    <h2>Consultas recibidas</h2>
                    <p class="text-muted mb-0">
                        Revisá los mensajes enviados desde el formulario público, contactá al cliente si hace falta y marcá las consultas pendientes como leídas.
                    </p>
                </div>
            </div>
        </div>

        <div class="admin-card p-0 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 admin-consultas-table">
                    <thead class="table-light">
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Consulta</th>
                            <th class="text-center">Fecha</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($consultas as $consulta)
                            @php
                                $telefonoLimpio = preg_replace('/\D+/', '', $consulta->telefono ?? '');
                                $whatsAppDisponible = filled($telefonoLimpio) && strlen($telefonoLimpio) >= 8;
                            @endphp
                            <tr>
                                <td>{{ $consulta->nombre_completo }}</td>
                                <td>{{ $consulta->correo }}</td>
                                <td>{{ $consulta->telefono ?: '-' }}</td>
                                <td>
                                    <div class="admin-consulta-message">
                                        {{ \Illuminate\Support\Str::limit($consulta->consulta, 120) }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    {{ $consulta->created_at?->format('d/m/Y H:i') }}
                                </td>
                                <td class="text-center">
                                    @if($consulta->leida)
                                        <span class="badge admin-home-badge admin-home-badge-visible">Leída</span>
                                    @else
                                        <span class="badge admin-home-badge admin-home-badge-hidden">Pendiente</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="admin-consultas-actions">
                                        @if(filled($consulta->correo))
                                            <a href="mailto:{{ $consulta->correo }}" class="btn btn-sm btn-outline-secondary">
                                                Enviar correo
                                            </a>
                                        @endif

                                        @if($whatsAppDisponible)
                                            <a
                                                href="https://wa.me/{{ $telefonoLimpio }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="btn btn-sm btn-outline-success"
                                            >
                                                WhatsApp
                                            </a>
                                        @endif

                                        @if(! $consulta->leida)
                                            <form action="{{ route('admin.consultas.leida', $consulta) }}" method="POST" class="d-inline-flex">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                                    Marcar como leída
                                                </button>
                                            </form>
                                        @else
                                            <span class="admin-marcas-status-note">Sin acciones pendientes</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    No hay consultas registradas todavía.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($consultas->hasPages())
            <div class="admin-card">
                <div class="admin-pagination-wrapper">
                    {{ $consultas->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection
