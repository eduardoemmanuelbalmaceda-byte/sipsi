@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Oficio {{ $oficio->numero_oficio }}</h2>
    <div>
        <a href="{{ route('oficios.edit', $oficio) }}" class="btn btn-warning">Editar</a>
        <a href="{{ route('oficios.index') }}" class="btn btn-secondary">Volver</a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header"><strong>Datos del oficio</strong></div>
            <div class="card-body">
                <p><strong>Número:</strong> {{ $oficio->numero_oficio }}</p>
                <p><strong>Juzgado:</strong> {{ $oficio->juzgado->nombre }}</p>
                <p><strong>Fecha de recepción:</strong> {{ $oficio->fecha_recepcion }}</p>
                <p><strong>Medio:</strong> {{ ucfirst($oficio->medio_recepcion) }}</p>
                <p><strong>Tipo de pedido:</strong> {{ $oficio->tipo_pedido }}</p>
                <p><strong>Estado:</strong>
                    <span class="badge bg-{{ $oficio->estado == 'cerrado' ? 'success' : ($oficio->estado == 'en_curso' ? 'warning' : 'secondary') }}">
                        {{ ucfirst($oficio->estado) }}
                    </span>
                </p>
                <p><strong>Observaciones:</strong> {{ $oficio->observaciones ?? '-' }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header"><strong>Paciente</strong></div>
            <div class="card-body">
                <p><strong>Nombre:</strong> {{ $oficio->paciente->apellido }}, {{ $oficio->paciente->nombre }}</p>
                <p><strong>DNI:</strong> {{ $oficio->paciente->dni }}</p>
                <p><strong>Teléfono:</strong> {{ $oficio->paciente->telefono ?? '-' }}</p>
                <a href="{{ route('pacientes.show', $oficio->paciente) }}" class="btn btn-sm btn-info">Ver historial</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Turno</strong>
                @if(!$oficio->turno)
                    <a href="{{ route('turnos.create', $oficio) }}" class="btn btn-sm btn-primary">+ Asignar turno</a>
                @else
                    <div class="d-flex gap-1">
                        <a href="{{ route('turnos.edit', $oficio->turno) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('turnos.destroy', $oficio->turno) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar este turno? El oficio volverá a estado Pendiente.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    </div>
                @endif
            </div>
            <div class="card-body">
                @if($oficio->turno)
                    <p><strong>Fecha:</strong> {{ $oficio->turno->fecha_turno }}</p>
                    <p><strong>Hora:</strong> {{ $oficio->turno->hora }}</p>
                    <p><strong>Profesional:</strong> {{ $oficio->turno->profesional->apellido }}, {{ $oficio->turno->profesional->nombre }}</p>
                    <p><strong>Estado:</strong> {{ ucfirst($oficio->turno->estado) }}</p>
                @else
                    <p class="text-muted">Sin turno asignado.</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Informe</strong>
                @if(!$oficio->informe && $oficio->turno)
                    <a href="{{ route('informes.create', $oficio) }}" class="btn btn-sm btn-primary">+ Cargar informe</a>
                @elseif($oficio->informe)
                    <div class="d-flex gap-1">
                        <a href="{{ route('informes.edit', $oficio->informe) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('informes.destroy', $oficio->informe) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar este informe? El oficio volverá a estado En curso.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    </div>
                @endif
            </div>
            <div class="card-body">
                @if($oficio->informe)
                    <p><strong>Fecha:</strong> {{ $oficio->informe->fecha_informe }}</p>
                    <p><strong>Profesional:</strong> {{ $oficio->informe->profesional->apellido }}, {{ $oficio->informe->profesional->nombre }}</p>
                    <p><strong>Enviado al juzgado:</strong> {{ $oficio->informe->enviado_juzgado ? 'Sí' : 'No' }}</p>
                    @if($oficio->informe->enviado_juzgado)
                        <p><strong>Fecha de envío:</strong> {{ $oficio->informe->fecha_envio }}</p>
                    @endif
                @elseif(!$oficio->turno)
                    <p class="text-muted">Primero asigná un turno.</p>
                @else
                    <p class="text-muted">Sin informe cargado.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection