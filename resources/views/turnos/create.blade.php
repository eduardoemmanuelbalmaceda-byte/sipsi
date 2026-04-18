@extends('layouts.app')
@section('content')
<h2 class="mb-4">Asignar turno — Oficio {{ $oficio->numero_oficio }}</h2>
<form action="{{ route('turnos.store') }}" method="POST">
    @csrf
    <input type="hidden" name="oficio_id" value="{{ $oficio->id }}">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Profesional</label>
            <select name="profesional_id" class="form-select @error('profesional_id') is-invalid @enderror">
                <option value="">Seleccionar profesional...</option>
                @foreach($profesionales as $profesional)
                <option value="{{ $profesional->id }}" {{ old('profesional_id') == $profesional->id ? 'selected' : '' }}>
                    {{ $profesional->apellido }}, {{ $profesional->nombre }} — {{ $profesional->especialidad }}
                </option>
                @endforeach
            </select>
            @error('profesional_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Fecha</label>
            <input type="date" name="fecha_turno" class="form-control @error('fecha_turno') is-invalid @enderror" value="{{ old('fecha_turno') }}">
            @error('fecha_turno')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Hora</label>
            <input type="time" name="hora" class="form-control @error('hora') is-invalid @enderror" value="{{ old('hora') }}">
            @error('hora')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <a href="{{ route('oficios.show', $oficio) }}" class="btn btn-secondary">Cancelar</a>
    <button type="submit" class="btn btn-primary">Guardar turno</button>
</form>
@endsection