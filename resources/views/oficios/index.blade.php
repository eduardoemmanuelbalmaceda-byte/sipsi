@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Oficios</h2>
    <a href="{{ route('oficios.create') }}" class="btn btn-primary">+ Nuevo oficio</a>
</div>
<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>Número</th>
            <th>Paciente</th>
            <th>Juzgado</th>
            <th>Fecha</th>
            <th>Medio</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($oficios as $oficio)
        <tr>
            <td>{{ $oficio->numero_oficio }}</td>
            <td>{{ $oficio->paciente->apellido }}, {{ $oficio->paciente->nombre }}</td>
            <td>{{ $oficio->juzgado->nombre }}</td>
            <td>{{ $oficio->fecha_recepcion }}</td>
            <td>{{ ucfirst($oficio->medio_recepcion) }}</td>
            <td>
                <span class="badge bg-{{ $oficio->estado == 'cerrado' ? 'success' : ($oficio->estado == 'en_curso' ? 'warning' : 'secondary') }}">
                    {{ ucfirst($oficio->estado) }}
                </span>
            </td>
            <td>
                <a href="{{ route('oficios.show', $oficio) }}" class="btn btn-sm btn-info">Ver</a>
                <a href="{{ route('oficios.edit', $oficio) }}" class="btn btn-sm btn-warning">Editar</a>
                <form action="{{ route('oficios.destroy', $oficio) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('¿Eliminar?')" class="btn btn-sm btn-danger">Eliminar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $oficios->links() }}
@endsection