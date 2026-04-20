@extends('layouts.app')
@section('content')

<div class="mb-4">
    <h2 class="page-header-title">Nuevo juzgado</h2>
    <p class="page-header-sub">Completá los datos del juzgado</p>
</div>

<div class="form-card">
    <form action="{{ route('juzgados.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" placeholder="Nombre del juzgado">
            @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Ciudad</label>
            <input type="text" name="ciudad" class="form-control @error('ciudad') is-invalid @enderror" value="{{ old('ciudad') }}" placeholder="Ciudad">
            @error('ciudad')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="form-label">Contacto</label>
            <input type="text" name="contacto" class="form-control" value="{{ old('contacto') }}" placeholder="Teléfono o email de contacto">
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('juzgados.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar juzgado</button>
        </div>
    </form>
</div>

@endsection
