@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-header-title">Profesionales</h2>
        <p class="page-header-sub">Equipo de salud mental</p>
    </div>
    <a href="{{ route('profesionales.create') }}" class="btn btn-primary">+ Nuevo profesional</a>
</div>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Apellido</th>
                <th>Nombre</th>
                <th>Especialidad</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($profesionales as $profesional)
            <tr>
                <td><strong>{{ $profesional->apellido }}</strong></td>
                <td>{{ $profesional->nombre }}</td>
                <td>{{ $profesional->especialidad }}</td>
                <td><span class="badge bg-info">{{ ucfirst($profesional->rol) }}</span></td>
                <td>
                    <a href="{{ route('profesionales.edit', $profesional) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('profesionales.destroy', $profesional) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('¿Eliminar este profesional?')" class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center py-4" style="color:#a0aac8;">No hay profesionales registrados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $profesionales->links() }}

@endsection
