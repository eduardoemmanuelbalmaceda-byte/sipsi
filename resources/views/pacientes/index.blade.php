@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-header-title">Pacientes</h2>
        <p class="page-header-sub">Listado de pacientes registrados</p>
    </div>
    <a href="{{ route('pacientes.create') }}" class="btn btn-primary">+ Nuevo paciente</a>
</div>

<form method="GET" action="{{ route('pacientes.index') }}" class="mb-3">
    <div class="search-bar">
        <div class="search-input-wrap">
            <svg class="search-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
            </svg>
            <input type="text" name="q" value="{{ $q ?? '' }}"
                placeholder="Buscar por nombre, apellido o DNI..."
                class="search-input" autocomplete="off" />
            @if($q)
                <a href="{{ route('pacientes.index') }}" class="search-clear">✕</a>
            @endif
        </div>
        <button type="submit" class="btn btn-primary btn-sm px-3">Buscar</button>
        @if($q)
            <a href="{{ route('pacientes.index') }}" class="btn btn-secondary btn-sm px-3">Limpiar</a>
        @endif
    </div>
</form>

@if($q)
    <p class="search-results-info">{{ $pacientes->total() }} resultado(s) para "<strong>{{ $q }}</strong>"</p>
@endif

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Apellido</th>
                <th>Nombre</th>
                <th>DNI</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pacientes as $paciente)
            <tr>
                <td><strong>{{ $paciente->apellido }}</strong></td>
                <td>{{ $paciente->nombre }}</td>
                <td>{{ $paciente->dni }}</td>
                <td>{{ $paciente->telefono ?? '—' }}</td>
                <td>
                    <a href="{{ route('pacientes.show', $paciente) }}" class="btn btn-sm btn-info">Ver</a>
                    <a href="{{ route('pacientes.edit', $paciente) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('pacientes.destroy', $paciente) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="button" onclick="confirmDelete(this.closest('form'))" class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center py-4" style="color:var(--text-muted);">
                    {{ $q ? 'No se encontraron resultados.' : 'No hay pacientes registrados.' }}
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $pacientes->links() }}

@endsection
