@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-header-title">Pacientes</h2>
        <p class="page-header-sub">Listado de pacientes registrados</p>
    </div>
    <div class="d-flex gap-2">
        <button onclick="document.getElementById('modalImportar').classList.add('active')" class="btn btn-secondary" style="display:flex;align-items:center;gap:0.4rem;">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            Importar Excel
        </button>
        <a href="{{ route('pacientes.create') }}" class="btn btn-primary">+ Nuevo paciente</a>
    </div>
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

{{-- ── Modal importar ── --}}
<div class="confirm-overlay" id="modalImportar">
    <div class="confirm-box" style="max-width:460px;text-align:left;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
            <div style="display:flex;align-items:center;gap:0.65rem;">
                <div class="confirm-icon" style="margin:0;background:rgba(139,167,217,0.12);">
                    <svg width="22" height="22" fill="none" stroke="var(--lavender)" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                </div>
                <div>
                    <div class="confirm-title" style="text-align:left;margin:0;">Importar pacientes</div>
                    <div style="font-size:0.78rem;color:var(--text-muted);">Desde archivo Excel o CSV</div>
                </div>
            </div>
            <button onclick="document.getElementById('modalImportar').classList.remove('active')"
                    style="background:none;border:none;cursor:pointer;color:var(--text-muted);font-size:1.2rem;line-height:1;">✕</button>
        </div>

        {{-- Instrucciones --}}
        <div style="background:var(--surface2);border-radius:0.6rem;padding:0.85rem 1rem;margin-bottom:1.25rem;font-size:0.82rem;color:var(--text-soft);">
            <strong style="display:block;margin-bottom:0.4rem;color:var(--text);">Formato requerido del archivo:</strong>
            El archivo debe tener estas columnas en la primera fila:
            <div style="margin-top:0.5rem;display:flex;flex-wrap:wrap;gap:0.3rem;">
                @foreach(['nombre','apellido','dni','fecha_nacimiento','telefono','direccion'] as $col)
                    <span style="background:var(--surface);border:1px solid var(--border);border-radius:4px;padding:2px 7px;font-size:0.75rem;font-family:monospace;color:var(--lavender);">{{ $col }}</span>
                @endforeach
            </div>
            <div style="margin-top:0.6rem;color:var(--text-muted);font-size:0.78rem;">
                Solo <strong>nombre</strong>, <strong>apellido</strong> y <strong>dni</strong> son obligatorios. Los DNI duplicados se omiten automáticamente.
            </div>
        </div>

        {{-- Descarga plantilla --}}
        <div style="margin-bottom:1rem;">
            <a href="{{ route('pacientes.plantilla') }}"
               style="font-size:0.8rem;color:var(--lavender);text-decoration:none;display:inline-flex;align-items:center;gap:0.3rem;">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17v3a1 1 0 001 1h16a1 1 0 001-1v-3"/>
                </svg>
                Descargar plantilla de ejemplo (.xlsx)
            </a>
        </div>

        {{-- Formulario --}}
        <form action="{{ route('pacientes.importar') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom:1rem;">
                <label style="font-size:0.82rem;font-weight:600;color:var(--text-soft);display:block;margin-bottom:0.4rem;">
                    Seleccioná el archivo
                </label>
                <input type="file" name="archivo" accept=".xlsx,.xls,.csv"
                       class="form-control" required>
                @error('archivo')
                    <div style="color:#dc2626;font-size:0.78rem;margin-top:0.3rem;">{{ $message }}</div>
                @enderror
            </div>
            <div class="confirm-actions" style="justify-content:flex-end;">
                <button type="button" class="btn btn-secondary"
                        onclick="document.getElementById('modalImportar').classList.remove('active')">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-primary">
                    Importar
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
