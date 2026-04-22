@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Registrar Nuevo Oficio Judicial</h2>
    <hr>
    <form action="{{ route('oficios.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Paciente</label>
            <select name="paciente_id" class="form-select" required>
                <option value="">-- Seleccione un Paciente --</option>
                @foreach($pacientes as $p)
                <option value="{{ $p->id }}">{{ $p->apellido }}, {{ $p->nombre }} ({{ $p->dni }})</option>
                @endforeach
            </select>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nro. Expediente</label>
                <input type="text" name="nro_expediente" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Juzgado</label>
                <input type="text" name="juzgado" class="form-control" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha de Recepción</label>
            <input type="date" name="fecha_recepcion" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Oficio</button>
    </form>
</div>
@endsection