@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>{{ $paciente->apellido }}, {{ $paciente->nombre }}</h2>
    <a href="{{ route('pacientes.index') }}" class="btn btn-secondary">Volver</a>
</div>
<div class="card mb-4">
    <div class="card-body">
        <p><strong>DNI:</strong> {{ $paciente->dni }}</p>
        <p><strong>Fecha de nacimiento:</strong> {{ $paciente->fecha_nacimiento ?? '-' }}</p>
        <p><strong>Teléfono:</strong> {{ $paciente->telefono ?? '-' }}</p>
        <p><strong>Dirección:</strong> {{ $paciente->direccion ?? '-' }}</p>
    </div>
</div>
<h4>Oficios vinculados</h4>
<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Número</th>
            <th>Juzgado</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($paciente->oficios as $oficio)
        <tr>
            <td>{{ $oficio->numero_oficio }}</td>
            <td>{{ $oficio->juzgado->nombre }}</td>
            <td>{{ $oficio->fecha_recepcion }}</td>
            <td><span class="badge bg-{{ $oficio->estado == 'cerrado' ? 'success' : ($oficio->estado == 'en_curso' ? 'warning' : 'secondary') }}">{{ $oficio->estado }}</span></td>
            <td><a href="{{ route('oficios.show', $oficio) }}" class="btn btn-sm btn-info">Ver</a></td>
        </tr>
        @empty
        <tr><td colspan="5">No tiene oficios registrados.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection