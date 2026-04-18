@extends('layouts.app')
@section('content')
<h2 class="mb-4">Editar turno</h2>
<form action="{{ route('turnos.update', $turno) }}" method="POST">
    @csrf @method('PUT')
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Profesional</label>
            <select name="profesional_id" class="form-select">
                @foreach($profesionales as $profesional)
                <option value="{{ $profesional->id }}" {{ $turno->profesional_id == $profesional->id ? 'selected' : '' }}>
                    {{ $profesional->apellido }}, {{ $profesional->nombre }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Fecha</label>
            <input type="date" name="fecha_turno" class="form-control" value="{{ old('fecha_turno', $turno->fecha_turno) }}">
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Hora</label>
            <input type="time" name="hora" class="form-control" value="{{ old('hora', $turno->hora) }}">
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Estado</label>
        <select name="estado" class="form-select">
            <option value="pendiente" {{ $turno->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
            <option value="realizado" {{ $turno->estado == 'realizado' ? 'selected' : '' }}>Realizado</option>
            <option value="ausente" {{ $turno->estado == 'ausente' ? 'selected' : '' }}>Ausente</option>
        </select>
    </div>
    <a href="{{ route('oficios.show', $turno->oficio_id) }}" class="btn btn-secondary">Cancelar</a>
    <button type="submit" class="btn btn-primary">Actualizar</button>
</form>
@endsection