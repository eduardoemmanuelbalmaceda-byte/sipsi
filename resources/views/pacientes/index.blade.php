@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Pacientes</h2>
    <a href="{{ route('pacientes.create') }}" class="btn btn-primary">+ Nuevo paciente</a>
</div>
<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>Apellido</th>
            <th>Nombre</th>
            <th>DNI</th>
            <th>Teléfono</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pacientes as $paciente)
        <tr>
            <td>{{ $paciente->apellido }}</td>
            <td>{{ $paciente->nombre }}</td>
            <td>{{ $paciente->dni }}</td>
            <td>{{ $paciente->telefono ?? '-' }}</td>
            <td>
                <a href="{{ route('pacientes.show', $paciente) }}" class="btn btn-sm btn-info">Ver</a>
                <a href="{{ route('pacientes.edit', $paciente) }}" class="btn btn-sm btn-warning">Editar</a>
                <form action="{{ route('pacientes.destroy', $paciente) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('¿Eliminar?')" class="btn btn-sm btn-danger">Eliminar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $pacientes->links() }}
@endsection