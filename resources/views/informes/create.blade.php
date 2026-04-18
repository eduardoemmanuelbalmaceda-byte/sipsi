@extends('layouts.app')
@section('content')
<h2 class="mb-4">Cargar informe — Oficio {{ $oficio->numero_oficio }}</h2>
<form action="{{ route('informes.store') }}" method="POST">
    @csrf
    <input type="hidden" name="oficio_id" value="{{ $oficio->id }}">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Profesional</label>
            <select name="profesional_id" class="form-select @error('profesional_id') is-invalid @enderror">
                <option value="">Seleccionar profesional...</option>
                @foreach($profesionales as $profesional)
                <option value="{{ $profesional->id }}" {{ old('profesional_id') == $profesional->id ? 'selected' : '' }}>
                    {{ $profesional->apellido }}, {{ $profesional->nombre }}
                </option>
                @endforeach
            </select>
            @error('profesional_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Fecha del informe</label>
            <input type="date" name="fecha_informe" class="form-control @error('fecha_informe') is-invalid @enderror" value="{{ old('fecha_informe') }}">
            @error('fecha_informe')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Contenido del informe</label>
        <textarea name="contenido" class="form-control @error('contenido') is-invalid @enderror" rows="6">{{ old('contenido') }}</textarea>
        @error('contenido')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <a href="{{ route('oficios.show', $oficio) }}" class="btn btn-secondary">Cancelar</a>
    <button type="submit" class="btn btn-primary">Guardar informe</button>
</form>
@endsection