@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Juzgados</h2>
    <a href="{{ route('juzgados.create') }}" class="btn btn-primary">+ Nuevo juzgado</a>
</div>
<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>Nombre</th>
            <th>Ciudad</th>
            <th>Contacto</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($juzgados as $juzgado)
        <tr>
            <td>{{ $juzgado->nombre }}</td>
            <td>{{ $juzgado->ciudad }}</td>
            <td>{{ $juzgado->contacto ?? '-' }}</td>
            <td>
                <a href="{{ route('juzgados.edit', $juzgado) }}" class="btn btn-sm btn-warning">Editar</a>
                <form action="{{ route('juzgados.destroy', $juzgado) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('¿Eliminar?')" class="btn btn-sm btn-danger">Eliminar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $juzgados->links() }}
@endsection