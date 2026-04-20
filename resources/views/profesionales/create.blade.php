@extends('layouts.app')
@section('content')

<div class="mb-4">
    <h2 class="page-header-title">Nuevo profesional</h2>
    <p class="page-header-sub">Completá los datos del profesional</p>
</div>

<div class="form-card" style="max-width:700px;">
    <form action="{{ route('profesionales.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Usuario del sistema</label>
            <select name="user_id" class="form-select @error('user_id') is-invalid @enderror">
                <option value="">Seleccionar usuario...</option>
                @foreach($users as $user)
                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }} — {{ $user->email }}
                </option>
                @endforeach
            </select>
            @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" placeholder="Nombre">
                @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Apellido</label>
                <input type="text" name="apellido" class="form-control @error('apellido') is-invalid @enderror" value="{{ old('apellido') }}" placeholder="Apellido">
                @error('apellido')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Especialidad</label>
                <input type="text" name="especialidad" class="form-control @error('especialidad') is-invalid @enderror" value="{{ old('especialidad') }}" placeholder="Ej: Psicología, Trabajo Social...">
                @error('especialidad')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Rol</label>
                <select name="rol" class="form-select @error('rol') is-invalid @enderror">
                    <option value="profesional" {{ old('rol') == 'profesional' ? 'selected' : '' }}>Profesional</option>
                    <option value="admin" {{ old('rol') == 'admin' ? 'selected' : '' }}>Administrativo</option>
                    <option value="direccion" {{ old('rol') == 'direccion' ? 'selected' : '' }}>Dirección</option>
                </select>
                @error('rol')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="d-flex gap-2 mt-2">
            <a href="{{ route('profesionales.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar profesional</button>
        </div>
    </form>
</div>

@endsection
