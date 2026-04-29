@extends('layouts.app')
@section('content')
<h2 class="mb-1">Informe de inasistencia — Oficio {{ $oficio->numero_oficio }}</h2>
<p class="text-muted mb-4">El paciente no asistió al turno. Completá el informe para notificar al juzgado y a Dirección.</p>

<form action="{{ route('informes.store') }}" method="POST">
    @csrf
    <input type="hidden" name="oficio_id" value="{{ $oficio->id }}">
    <input type="hidden" name="tipo" value="inasistencia">

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
            <input type="date" name="fecha_informe" class="form-control @error('fecha_informe') is-invalid @enderror"
                   value="{{ old('fecha_informe', now()->toDateString()) }}">
            @error('fecha_informe')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Descripción de la inasistencia</label>
        <textarea name="contenido" class="form-control @error('contenido') is-invalid @enderror" rows="5"
                  placeholder="Describí la situación: fecha del turno, motivo conocido de la inasistencia, acciones tomadas...">{{ old('contenido') }}</textarea>
        @error('contenido')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('oficios.show', $oficio) }}" class="btn btn-secondary">Cancelar</a>
        <button type="submit" class="btn btn-warning">Guardar informe de inasistencia</button>
    </div>
</form>
@endsection
