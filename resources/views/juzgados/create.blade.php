@extends('layouts.app')
@section('content')
<h2 class="mb-4">Nuevo juzgado</h2>
<form action="{{ route('juzgados.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}">
        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Ciudad</label>
        <input type="text" name="ciudad" class="form-control @error('ciudad') is-invalid @enderror" value="{{ old('ciudad') }}">
        @error('ciudad')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Contacto</label>
        <input type="text" name="contacto" class="form-control" value="{{ old('contacto') }}">
    </div>
    <a href="{{ route('juzgados.index') }}" class="btn btn-secondary">Cancelar</a>
    <button type="submit" class="btn btn-primary">Guardar</button>
</form>
@endsection