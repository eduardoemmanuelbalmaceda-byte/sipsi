@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-header-title">Oficios</h2>
        <p class="page-header-sub">Listado de oficios judiciales</p>
    </div>
    <a href="{{ route('oficios.create') }}" class="btn btn-primary">+ Nuevo oficio</a>
</div>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
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
            @forelse($oficios as $oficio)
            <tr>
                <td><strong>{{ $oficio->numero_oficio }}</strong></td>
                <td>{{ $oficio->paciente->apellido }}, {{ $oficio->paciente->nombre }}</td>
                <td>{{ $oficio->juzgado->nombre }}</td>
                <td>{{ \Carbon\Carbon::parse($oficio->fecha_recepcion)->format('d/m/Y') }}</td>
                <td>{{ ucfirst($oficio->medio_recepcion) }}</td>
                <td>
                    @if($oficio->estado == 'cerrado')
                        <span class="badge bg-success">Cerrado</span>
                    @elseif($oficio->estado == 'en_curso')
                        <span class="badge bg-warning">En curso</span>
                    @else
                        <span class="badge bg-secondary">{{ ucfirst($oficio->estado) }}</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('oficios.show', $oficio) }}" class="btn btn-sm btn-info">Ver</a>
                    <a href="{{ route('oficios.edit', $oficio) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('oficios.destroy', $oficio) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('¿Eliminar este oficio?')" class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-4" style="color:#a0aac8;">No hay oficios registrados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $oficios->links() }}

@endsection
