@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Asignar Nuevo Turno</h2>
    <hr>
    <form action="{{ route('turnos.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Seleccionar Oficio (Paciente - Expediente)</label>
            <select name="oficio_id" class="form-select" required>
                <option value="">-- Seleccione un Oficio --</option>
                @foreach($oficios as $o)
                <option value="{{ $o->id }}">
                    {{ $o->paciente->apellido }} - Exp: {{ $o->nro_expediente }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Profesional Asignado</label>
                <select name="user_id" class="form-select" required>
                    <option value="">-- Seleccione Profesional --</option>
                    @foreach($profesionales as $u)
                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Fecha y Hora</label>
                <input type="datetime-local" name="fecha_hora" class="form-control" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Estado Inicial</label>
            <select name="estado" class="form-select">
                <option value="pendiente">Pendiente</option>
                <option value="asistio">Asistió</option>
                <option value="no_asistio">No asistió</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Confirmar Turno</button>
        <a href="{{ route('turnos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection