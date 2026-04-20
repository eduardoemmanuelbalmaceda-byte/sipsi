@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-header-title">Juzgados</h2>
        <p class="page-header-sub">Listado de juzgados registrados</p>
    </div>
    <a href="{{ route('juzgados.create') }}" class="btn btn-primary">+ Nuevo juzgado</a>
</div>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Ciudad</th>
                <th>Contacto</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($juzgados as $juzgado)
            <tr>
                <td><strong>{{ $juzgado->nombre }}</strong></td>
                <td>{{ $juzgado->ciudad }}</td>
                <td>{{ $juzgado->contacto ?? '—' }}</td>
                <td>
                    <a href="{{ route('juzgados.edit', $juzgado) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('juzgados.destroy', $juzgado) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('¿Eliminar este juzgado?')" class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center py-4" style="color:#a0aac8;">No hay juzgados registrados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $juzgados->links() }}

@endsection
