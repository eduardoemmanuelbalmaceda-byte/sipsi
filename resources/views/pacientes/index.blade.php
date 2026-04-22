@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <h2>Listado de Pacientes</h2>
        <a href="{{ route('pacientes.create') }}" class="btn btn-primary">Nuevo Paciente</a>
    </div>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>DNI</th>
                <th>Nombre y Apellido</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pacientes as $paciente)
            <tr>
                <td>{{ $paciente->dni }}</td>
                <td>{{ $paciente->nombre }} {{ $paciente->apellido }}</td>
                <td>{{ $paciente->telefono }}</td>
                <td>
                    <a href="#" class="btn btn-sm btn-info">Ver Oficios</a>
                </td>
            </tr>
            @endforeach

            @if($pacientes->isEmpty())
            <tr>
                <td colspan="4" class="text-center">No hay pacientes registrados aún.</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection