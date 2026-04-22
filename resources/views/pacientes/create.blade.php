@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Registrar Nuevo Paciente</h2>
    <hr>
    <form action="{{ route('pacientes.store') }}" method="POST">
        @csrf <div class="mb-3">
            <label class="form-label">DNI</label>
            <input type="text" name="dni" class="form-control" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Apellido</label>
                <input type="text" name="apellido" class="form-control" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Guardar Paciente</button>
        <a href="{{ route('pacientes.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection