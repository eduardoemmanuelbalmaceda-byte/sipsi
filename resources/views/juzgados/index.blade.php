@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-header-title">Juzgados</h2>
        <p class="page-header-sub">Listado de juzgados registrados</p>
    </div>
    <a href="{{ route('juzgados.create') }}" class="btn btn-primary">+ Nuevo juzgado</a>
</div>

<form method="GET" action="{{ route('juzgados.index') }}" class="mb-3">
    <div class="search-bar">
        <div class="search-input-wrap">
            <svg class="search-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
            </svg>
            <input type="text" name="q" value="{{ $q ?? '' }}"
                placeholder="Buscar por nombre o ciudad..."
                class="search-input" autocomplete="off" />
            @if($q)
                <a href="{{ route('juzgados.index') }}" class="search-clear">✕</a>
            @endif
        </div>
        <button type="submit" class="btn btn-primary btn-sm px-3">Buscar</button>
        @if($q)
            <a href="{{ route('juzgados.index') }}" class="btn btn-secondary btn-sm px-3">Limpiar</a>
        @endif
    </div>
</form>

@if($q)
    <p class="search-results-info">{{ $juzgados->total() }} resultado(s) para "<strong>{{ $q }}</strong>"</p>
@endif

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Ciudad</th>
                <th>Contacto</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($juzgados as $juzgado)
            <tr>
                <td><strong>{{ $juzgado->nombre }}</strong></td>
                <td>{{ $juzgado->ciudad }}</td>
                <td>{{ $juzgado->contacto ?? '—' }}</td>
                <td>
                    <a href="{{ route('juzgados.edit', $juzgado) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('juzgados.destroy', $juzgado) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="button" onclick="confirmDelete(this.closest('form'))" class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center py-4" style="color:var(--text-muted);">
                    {{ $q ? 'No se encontraron resultados.' : 'No hay juzgados registrados.' }}
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $juzgados->links() }}

@endsection
