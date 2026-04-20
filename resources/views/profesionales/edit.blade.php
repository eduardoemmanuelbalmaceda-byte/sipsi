@extends('layouts.app')
@section('content')

<div class="mb-4">
    <h2 class="page-header-title">Editar profesional</h2>
    <p class="page-header-sub">{{ $profesional->apellido }}, {{ $profesional->nombre }}</p>
</div>

<div class="form-card" style="max-width:700px;">
    <form action="{{ route('profesionales.update', $profesional->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $profesional->nombre) }}">
                @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Apellido</label>
                <input type="text" name="apellido" class="form-control @error('apellido') is-invalid @enderror" value="{{ old('apellido', $profesional->apellido) }}">
                @error('apellido')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Especialidad</label>
                <input type="text" name="especialidad" class="form-control @error('especialidad') is-invalid @enderror" value="{{ old('especialidad', $profesional->especialidad) }}">
                @error('especialidad')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Rol</label>
                <select name="rol" class="form-select">
                    <option value="profesional" {{ $profesional->rol == 'profesional' ? 'selected' : '' }}>Profesional</option>
                    <option value="admin"       {{ $profesional->rol == 'admin'       ? 'selected' : '' }}>Administrativo</option>
                    <option value="direccion"   {{ $profesional->rol == 'direccion'   ? 'selected' : '' }}>Dirección</option>
                </select>
            </div>
        </div>
        <div class="d-flex gap-2 mt-2">
            <a href="{{ route('profesionales.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </div>
    </form>
</div>

@endsection
