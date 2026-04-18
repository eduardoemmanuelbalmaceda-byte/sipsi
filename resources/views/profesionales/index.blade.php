@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Profesionales</h2>
    <a href="{{ route('profesionales.create') }}" class="btn btn-primary">+ Nuevo profesional</a>
</div>
<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>Apellido</th>
            <th>Nombre</th>
            <th>Especialidad</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($profesionales as $profesional)
        <tr>
            <td>{{ $profesional->apellido }}</td>
            <td>{{ $profesional->nombre }}</td>
            <td>{{ $profesional->especialidad }}</td>
            <td>{{ ucfirst($profesional->rol) }}</td>
            <td>
                <a href="{{ route('profesionales.edit', $profesional) }}" class="btn btn-sm btn-warning">Editar</a>
                <form action="{{ route('profesionales.destroy', $profesional) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('¿Eliminar?')" class="btn btn-sm btn-danger">Eliminar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $profesionales->links() }}
@endsection