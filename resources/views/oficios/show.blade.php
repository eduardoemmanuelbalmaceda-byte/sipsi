@extends('layouts.app')
@section('content')

{{-- Encabezado --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-header-title mb-0">Oficio {{ $oficio->numero_oficio }}</h2>
        <p class="page-header-sub">{{ $oficio->juzgado->nombre }} · {{ $oficio->paciente->apellido }}, {{ $oficio->paciente->nombre }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('oficios.edit', $oficio) }}" class="btn btn-warning btn-sm">Editar</a>
        <a href="{{ route('oficios.index') }}" class="btn btn-secondary btn-sm">Volver</a>
    </div>
</div>

{{-- ══════════════════════════════════════════
     BARRA DE PROGRESO DEL FLUJO
══════════════════════════════════════════ --}}
@php
    $paso = 1;
    if ($oficio->turno)              $paso = 2;
    if ($oficio->notificado_por)     $paso = 3;
    if ($oficio->confirmacion_juzgado) $paso = 4;
    if ($oficio->turno && $oficio->turno->asistencia !== 'pendiente') $paso = 5;
    if ($oficio->informe)            $paso = 6;
    if ($oficio->informe && ($oficio->informe->enviado_juzgado || $oficio->informe->enviado_direccion)) $paso = 7;
    if ($oficio->informe && $oficio->informe->enviado_juzgado && $oficio->informe->enviado_direccion)   $paso = 8;
    $pasos = [
        1 => 'Recepción',
        2 => 'Turno',
        3 => 'Notificación',
        4 => 'Confirmación',
        5 => 'Día del turno',
        6 => 'Informe',
        7 => 'Envío',
        8 => 'Cierre',
    ];
@endphp

<div class="card mb-4 p-3">
    <div style="display:flex; align-items:center; gap:0; overflow-x:auto;">
        @foreach($pasos as $num => $label)
            @php
                $done    = $paso >= $num;
                $current = $paso === $num;
            @endphp
            <div style="display:flex; align-items:center; flex:1; min-width:60px;">
                <div style="display:flex; flex-direction:column; align-items:center; flex:1;">
                    <div style="
                        width:32px; height:32px; border-radius:50%;
                        background: {{ $done ? 'linear-gradient(135deg,var(--mint),var(--green))' : 'var(--surface2)' }};
                        border: 2px solid {{ $current ? 'var(--green)' : ($done ? 'transparent' : 'var(--border)') }};
                        display:flex; align-items:center; justify-content:center;
                        font-size:0.75rem; font-weight:700;
                        color: {{ $done ? '#fff' : 'var(--text-muted)' }};
                        box-shadow: {{ $current ? '0 0 0 3px rgba(90,158,122,0.25)' : 'none' }};
                        transition: all 0.3s;
                    ">
                        @if($done && !$current)
                            <svg width="14" height="14" fill="none" stroke="white" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        @else
                            {{ $num }}
                        @endif
                    </div>
                    <span style="font-size:0.65rem; color:{{ $done ? 'var(--green)' : 'var(--text-muted)' }}; font-weight:{{ $current ? '700' : '500' }}; margin-top:4px; white-space:nowrap;">
                        {{ $label }}
                    </span>
                </div>
                @if($num < count($pasos))
                    <div style="height:2px; flex:1; background:{{ $paso > $num ? 'var(--mint)' : 'var(--border)' }}; margin-bottom:18px; transition:background 0.3s;"></div>
                @endif
            </div>
        @endforeach
    </div>
</div>

{{-- ══════════════════════════════════════════
     DATOS GENERALES
══════════════════════════════════════════ --}}
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header fw-bold">Datos del oficio</div>
            <div class="card-body">
                <p><strong>Número:</strong> {{ $oficio->numero_oficio }}</p>
                <p><strong>Juzgado:</strong> {{ $oficio->juzgado->nombre }}</p>
                <p><strong>Fecha de recepción:</strong> {{ \Carbon\Carbon::parse($oficio->fecha_recepcion)->format('d/m/Y') }}</p>
                @if($oficio->fecha_vencimiento)
                <p><strong>Vencimiento:</strong> {{ \Carbon\Carbon::parse($oficio->fecha_vencimiento)->format('d/m/Y') }}</p>
                @endif
                <p><strong>Medio:</strong> {{ ucfirst($oficio->medio_recepcion) }}</p>
                <p><strong>Tipo de pedido:</strong> {{ $oficio->tipo_pedido }}</p>
                <p><strong>Estado:</strong>
                    <span class="badge bg-{{ $oficio->estado == 'cerrado' ? 'success' : ($oficio->estado == 'en_curso' ? 'warning' : 'secondary') }}">
                        {{ ucfirst(str_replace('_', ' ', $oficio->estado)) }}
                    </span>
                </p>
                @if($oficio->observaciones)
                <p><strong>Observaciones:</strong> {{ $oficio->observaciones }}</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header fw-bold">Paciente</div>
            <div class="card-body">
                <p><strong>Nombre:</strong> {{ $oficio->paciente->apellido }}, {{ $oficio->paciente->nombre }}</p>
                <p><strong>DNI:</strong> {{ $oficio->paciente->dni }}</p>
                <p><strong>Teléfono:</strong> {{ $oficio->paciente->telefono ?? '-' }}</p>
                <p><strong>Dirección:</strong> {{ $oficio->paciente->direccion ?? '-' }}</p>
                <a href="{{ route('pacientes.show', $oficio->paciente) }}" class="btn btn-sm btn-info">Ver historial</a>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     PASO 2 — TURNO
══════════════════════════════════════════ --}}
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>
            <span style="color:var(--mint);">②</span> Turno asignado
        </strong>
        @if(!$oficio->turno)
            <a href="{{ route('turnos.create', $oficio) }}" class="btn btn-sm btn-primary">+ Asignar turno</a>
        @else
            <div class="d-flex gap-1">
                <a href="{{ route('turnos.edit', $oficio->turno) }}" class="btn btn-sm btn-warning">Editar</a>
                <form action="{{ route('turnos.destroy', $oficio->turno) }}" method="POST"
                      onsubmit="return confirm('¿Eliminar este turno?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                </form>
            </div>
        @endif
    </div>
    <div class="card-body">
        @if($oficio->turno)
            <div class="row">
                <div class="col-md-3"><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($oficio->turno->fecha_turno)->format('d/m/Y') }}</div>
                <div class="col-md-3"><strong>Hora:</strong> {{ \Carbon\Carbon::parse($oficio->turno->hora)->format('H:i') }}</div>
                <div class="col-md-4"><strong>Profesional:</strong> {{ $oficio->turno->profesional->apellido }}, {{ $oficio->turno->profesional->nombre }}</div>
                <div class="col-md-2">
                    <strong>Estado:</strong>
                    <span class="badge bg-{{ $oficio->turno->estado == 'realizado' ? 'success' : ($oficio->turno->estado == 'ausente' ? 'warning' : 'secondary') }}">
                        {{ ucfirst($oficio->turno->estado) }}
                    </span>
                </div>
            </div>
        @else
            <p class="text-muted mb-0">Sin turno asignado.</p>
        @endif
    </div>
</div>

{{-- ══════════════════════════════════════════
     PASO 3 — NOTIFICACIÓN AL PACIENTE
══════════════════════════════════════════ --}}
@if($oficio->turno)
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong><span style="color:var(--mint);">③</span> Notificación al paciente</strong>
        @if(!$oficio->notificado_por)
            <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNotif">
                Registrar notificación
            </button>
        @endif
    </div>
    <div class="card-body">
        @if($oficio->notificado_por)
            <p class="mb-0">
                <strong>Notificado por:</strong>
                <span class="badge bg-info">
                    {{ $oficio->notificado_por === 'direccion' ? 'Dirección' : ($oficio->notificado_por === 'juzgado' ? 'Juzgado' : 'Conflicto — informado al juzgado') }}
                </span>
            </p>
        @else
            <div class="collapse" id="collapseNotif">
                <form action="{{ route('oficios.notificacion', $oficio) }}" method="POST" class="mt-2">
                    @csrf @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label">¿Quién notifica al paciente?</label>
                        <div class="d-flex gap-3 flex-wrap">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="notificado_por" value="direccion" id="notifDir">
                                <label class="form-check-label" for="notifDir">Dirección</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="notificado_por" value="juzgado" id="notifJuz">
                                <label class="form-check-label" for="notifJuz">Juzgado</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="notificado_por" value="conflicto" id="notifConf">
                                <label class="form-check-label" for="notifConf">Conflicto — informar al juzgado</label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
                </form>
            </div>
            @if(!$oficio->notificado_por)
                <p class="text-muted mb-0">Pendiente de registrar.</p>
            @endif
        @endif
    </div>
</div>
@endif

{{-- ══════════════════════════════════════════
     PASO 4 — CONFIRMACIÓN DEL JUZGADO
══════════════════════════════════════════ --}}
@if($oficio->notificado_por)
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong><span style="color:var(--mint);">④</span> Confirmación del juzgado</strong>
    </div>
    <div class="card-body">
        @if($oficio->confirmacion_juzgado)
            <p class="mb-0">
                <span class="badge bg-success">✓ Confirmado el {{ \Carbon\Carbon::parse($oficio->fecha_confirmacion_juzgado)->format('d/m/Y') }}</span>
            </p>
        @else
            <div class="d-flex align-items-center gap-3">
                <p class="text-muted mb-0">Esperando confirmación del juzgado.</p>
                <form action="{{ route('oficios.confirmarJuzgado', $oficio) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-sm btn-success"
                            onclick="return confirm('¿Confirmar recepción del juzgado?')">
                        Marcar como confirmado
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endif

{{-- ══════════════════════════════════════════
     PASO 5 — DÍA DEL TURNO / ASISTENCIA
══════════════════════════════════════════ --}}
@if($oficio->turno)
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong><span style="color:var(--mint);">⑤</span> Día del turno — ¿El paciente asistió?</strong>
    </div>
    <div class="card-body">
        @if($oficio->turno->asistencia === 'asistio')
            <span class="badge bg-success">✓ El paciente asistió</span>
        @elseif($oficio->turno->asistencia === 'no_asistio')
            <span class="badge bg-warning">✗ El paciente no asistió</span>
            @if($oficio->turno->motivo_inasistencia)
                <p class="mt-2 mb-0"><strong>Motivo:</strong> {{ $oficio->turno->motivo_inasistencia }}</p>
            @endif
        @else
            <form action="{{ route('turnos.asistencia', $oficio->turno) }}" method="POST">
                @csrf @method('PATCH')
                <div class="row align-items-end g-3">
                    <div class="col-md-4">
                        <label class="form-label">Asistencia</label>
                        <select name="asistencia" class="form-select" id="selectAsistencia" onchange="toggleMotivo(this.value)">
                            <option value="">Seleccionar...</option>
                            <option value="asistio">Sí asistió</option>
                            <option value="no_asistio">No asistió</option>
                        </select>
                    </div>
                    <div class="col-md-5" id="motivoWrap" style="display:none;">
                        <label class="form-label">Motivo de inasistencia</label>
                        <input type="text" name="motivo_inasistencia" class="form-control" placeholder="Opcional...">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">Registrar</button>
                    </div>
                </div>
            </form>
            <script>
                function toggleMotivo(val) {
                    document.getElementById('motivoWrap').style.display = val === 'no_asistio' ? 'block' : 'none';
                }
            </script>
        @endif
    </div>
</div>
@endif

{{-- ══════════════════════════════════════════
     PASO 6 — INFORME
══════════════════════════════════════════ --}}
@if($oficio->turno && $oficio->turno->asistencia !== 'pendiente')
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>
            <span style="color:var(--mint);">⑥</span>
            @if($oficio->turno->asistencia === 'asistio')
                Informe clínico
            @else
                Informe de inasistencia
            @endif
        </strong>
        @if(!$oficio->informe)
            @if($oficio->turno->asistencia === 'asistio')
                <a href="{{ route('informes.create', $oficio) }}" class="btn btn-sm btn-primary">+ Cargar informe clínico</a>
            @else
                <a href="{{ route('informes.createInasistencia', $oficio) }}" class="btn btn-sm btn-warning">+ Cargar informe de inasistencia</a>
            @endif
        @else
            <div class="d-flex gap-1">
                <a href="{{ route('informes.pdf', $oficio->informe) }}" class="btn btn-sm btn-success" target="_blank">PDF</a>
                <a href="{{ route('informes.edit', $oficio->informe) }}" class="btn btn-sm btn-warning">Editar</a>
                <form action="{{ route('informes.destroy', $oficio->informe) }}" method="POST"
                      onsubmit="return confirm('¿Eliminar este informe?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                </form>
            </div>
        @endif
    </div>
    <div class="card-body">
        @if($oficio->informe)
            <div class="row">
                <div class="col-md-4">
                    <strong>Tipo:</strong>
                    <span class="badge bg-{{ $oficio->informe->tipo === 'clinico' ? 'info' : 'warning' }}">
                        {{ $oficio->informe->tipo === 'clinico' ? 'Clínico' : 'Inasistencia' }}
                    </span>
                </div>
                <div class="col-md-4"><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($oficio->informe->fecha_informe)->format('d/m/Y') }}</div>
                <div class="col-md-4"><strong>Profesional:</strong> {{ $oficio->informe->profesional->apellido }}, {{ $oficio->informe->profesional->nombre }}</div>
            </div>
        @else
            <p class="text-muted mb-0">Sin informe cargado.</p>
        @endif
    </div>
</div>
@endif

{{-- ══════════════════════════════════════════
     PASO 7 — ENVÍO A DIRECCIÓN Y JUZGADO
══════════════════════════════════════════ --}}
@if($oficio->informe)
<div class="card mb-3">
    <div class="card-header">
        <strong><span style="color:var(--mint);">⑦</span> Envío del informe</strong>
    </div>
    <div class="card-body">
        <div class="row g-3">
            {{-- Envío a Juzgado --}}
            <div class="col-md-6">
                <div class="p-3 rounded" style="background:var(--surface2); border:1px solid var(--border);">
                    <strong>Juzgado</strong><br>
                    @if($oficio->informe->enviado_juzgado)
                        <span class="badge bg-success mt-1">✓ Enviado el {{ \Carbon\Carbon::parse($oficio->informe->fecha_envio)->format('d/m/Y') }}</span>
                    @else
                        <span class="badge bg-warning mt-1">Pendiente</span>
                        <form action="{{ route('informes.marcarEnviado', $oficio->informe) }}" method="POST" class="mt-2">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-success"
                                    onclick="return confirm('¿Confirmar envío al juzgado?')">
                                Marcar como enviado
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            {{-- Envío a Dirección --}}
            <div class="col-md-6">
                <div class="p-3 rounded" style="background:var(--surface2); border:1px solid var(--border);">
                    <strong>Dirección</strong><br>
                    @if($oficio->informe->enviado_direccion)
                        <span class="badge bg-success mt-1">✓ Enviado el {{ \Carbon\Carbon::parse($oficio->informe->fecha_envio_direccion)->format('d/m/Y') }}</span>
                    @else
                        <span class="badge bg-warning mt-1">Pendiente</span>
                        <form action="{{ route('informes.marcarEnviadoDireccion', $oficio->informe) }}" method="POST" class="mt-2">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-success"
                                    onclick="return confirm('¿Confirmar envío a Dirección?')">
                                Marcar como enviado
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ══════════════════════════════════════════
     PASO 8 — CIERRE ADMINISTRATIVO
══════════════════════════════════════════ --}}
@if($oficio->informe && $oficio->informe->enviado_juzgado && $oficio->informe->enviado_direccion)
<div class="card mb-3" style="border-left: 4px solid var(--green) !important;">
    <div class="card-body d-flex align-items-center gap-3">
        <div style="width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,var(--mint),var(--green));display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="22" height="22" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <div>
            <strong style="color:var(--green);">⑧ Cierre administrativo completado</strong>
            <p class="mb-0 text-muted" style="font-size:0.85rem;">El informe fue enviado al juzgado y a Dirección. El proceso está finalizado.</p>
        </div>
    </div>
</div>
@endif

@endsection
