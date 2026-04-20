@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-header-title">Oficios</h2>
        <p class="page-header-sub">Listado de oficios judiciales</p>
    </div>
    <a href="{{ route('oficios.create') }}" class="btn btn-primary">+ Nuevo oficio</a>
</div>

{{-- Búsqueda y filtros --}}
<form method="GET" action="{{ route('oficios.index') }}" class="mb-3">
    <div class="search-bar">
        <div class="search-input-wrap">
            <svg class="search-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
            </svg>
            <input type="text" name="q" value="{{ $q ?? '' }}"
                placeholder="Buscar por número, paciente, juzgado..."
                class="search-input" autocomplete="off" />
            @if($q)
                <a href="{{ route('oficios.index') }}" class="search-clear">✕</a>
            @endif
        </div>

        <select name="estado" class="filter-select" onchange="this.form.submit()">
            <option value="">Todos los estados</option>
            <option value="pendiente"  {{ ($estado ?? '') == 'pendiente'  ? 'selected' : '' }}>Pendiente</option>
            <option value="en_curso"   {{ ($estado ?? '') == 'en_curso'   ? 'selected' : '' }}>En curso</option>
            <option value="cerrado"    {{ ($estado ?? '') == 'cerrado'    ? 'selected' : '' }}>Cerrado</option>
        </select>

        <button type="submit" class="btn btn-primary btn-sm px-3">Buscar</button>

        @if($q || $estado)
            <a href="{{ route('oficios.index') }}" class="btn btn-secondary btn-sm px-3">Limpiar</a>
        @endif
    </div>
</form>

@if($q || $estado)
    <p class="search-results-info">
        {{ $oficios->total() }} resultado(s)
        @if($q) para "<strong>{{ $q }}</strong>"@endif
        @if($estado) · estado: <strong>{{ $estado }}</strong>@endif
    </p>
@endif

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Número</th>
                <th>Paciente</th>
                <th>Juzgado</th>
                <th>Fecha</th>
                <th>Medio</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($oficios as $oficio)
            <tr>
                <td><strong>{{ $oficio->numero_oficio }}</strong></td>
                <td>{{ $oficio->paciente->apellido }}, {{ $oficio->paciente->nombre }}</td>
                <td>{{ $oficio->juzgado->nombre }}</td>
                <td>{{ \Carbon\Carbon::parse($oficio->fecha_recepcion)->format('d/m/Y') }}</td>
                <td>{{ ucfirst($oficio->medio_recepcion) }}</td>
                <td>
                    @if($oficio->estado == 'cerrado')
                        <span class="badge bg-success">Cerrado</span>
                    @elseif($oficio->estado == 'en_curso')
                        <span class="badge bg-warning">En curso</span>
                    @else
                        <span class="badge bg-secondary">Pendiente</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('oficios.show', $oficio) }}" class="btn btn-sm btn-info">Ver</a>
                    <a href="{{ route('oficios.edit', $oficio) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('oficios.destroy', $oficio) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="button" onclick="confirmDelete(this.closest('form'))" class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-4" style="color:var(--text-muted);">
                    {{ $q || $estado ? 'No se encontraron resultados.' : 'No hay oficios registrados.' }}
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $oficios->links() }}

@endsection
