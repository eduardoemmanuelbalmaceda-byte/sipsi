@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <h2>Control de Oficios Judiciales</h2>
        <a href="{{ route('oficios.create') }}" class="btn btn-primary">Nuevo Oficio</a>
    </div>

    <table class="table table-hover table-bordered bg-white shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>Expediente</th>
                <th>Paciente</th>
                <th>Juzgado</th>
                <th>Fecha Rec.</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($oficios as $oficio)
            <tr>
                <td><strong>{{ $oficio->nro_expediente }}</strong></td>
                <td>{{ $oficio->paciente->apellido }}, {{ $oficio->paciente->nombre }}</td>
                <td>{{ $oficio->juzgado }}</td>
                <td>{{ \Carbon\Carbon::parse($oficio->fecha_recepcion)->format('d/m/Y') }}</td>
                <td>
                    <span class="badge {{ $oficio->estado == 'finalizado' ? 'bg-success' : 'bg-warning text-dark' }}">
                        {{ strtoupper($oficio->estado) }}
                    </span>
                </td>
                <td>
                    <a href="#" class="btn btn-sm btn-outline-secondary">Gestionar</a>
                </td>
            </tr>
            @endforeach

            @if($oficios->isEmpty())
            <tr>
                <td colspan="6" class="text-center">No hay oficios registrados.</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection