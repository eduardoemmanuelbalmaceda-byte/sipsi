@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <h2>Agenda de Turnos</h2>
        <a href="{{ route('turnos.create') }}" class="btn btn-primary">Asignar Nuevo Turno</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Fecha y Hora</th>
                        <th>Paciente</th>
                        <th>Expediente</th>
                        <th>Profesional</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($turnos as $turno)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($turno->fecha_hora)->format('d/m/Y H:i') }}</td>
                        <td>{{ $turno->oficio->paciente->apellido }}, {{ $turno->oficio->paciente->nombre }}</td>
                        <td>{{ $turno->oficio->nro_expediente }}</td>
                        <td>{{ $turno->profesional->name }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ strtoupper($turno->estado) }}</span>
                        </td>
                    </tr>
                    @endforeach

                    @if($turnos->isEmpty())
                    <tr>
                        <td colspan="5" class="text-center text-muted">No hay turnos programados aún.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection