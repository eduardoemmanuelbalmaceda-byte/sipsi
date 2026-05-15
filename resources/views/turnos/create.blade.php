@extends('layouts.app')
@section('content')
<h2 class="mb-4">Asignar turno — Oficio {{ $oficio->numero_oficio }}</h2>

@if($oficio->paciente && $oficio->paciente->telefono)
    <div class="alert alert-info mb-3">
        <svg width="16" height="16" fill="currentColor" class="me-2" viewBox="0 0 16 16" style="display:inline-block;vertical-align:middle;">
            <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
        </svg>
        <strong>WhatsApp disponible:</strong> {{ $oficio->paciente->nombre }} {{ $oficio->paciente->apellido }} - {{ $oficio->paciente->telefono }}
    </div>
@endif

<form action="{{ route('turnos.store') }}" method="POST">
    @csrf
    <input type="hidden" name="oficio_id" value="{{ $oficio->id }}">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Profesional</label>
            <select name="profesional_id" class="form-select @error('profesional_id') is-invalid @enderror">
                <option value="">Seleccionar profesional...</option>
                @foreach($profesionales as $profesional)
                <option value="{{ $profesional->id }}" {{ old('profesional_id') == $profesional->id ? 'selected' : '' }}>
                    {{ $profesional->apellido }}, {{ $profesional->nombre }} — {{ $profesional->especialidad }}
                </option>
                @endforeach
            </select>
            @error('profesional_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Fecha</label>
            <input type="date" name="fecha_turno" class="form-control @error('fecha_turno') is-invalid @enderror" value="{{ old('fecha_turno') }}">
            @error('fecha_turno')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Hora</label>
            <input type="time" name="hora" class="form-control @error('hora') is-invalid @enderror" value="{{ old('hora') }}">
            @error('hora')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    @if($oficio->paciente && $oficio->paciente->telefono)
    <div class="mb-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="enviar_whatsapp" id="enviarWhatsapp" value="1" checked>
            <label class="form-check-label" for="enviarWhatsapp">
                <svg width="16" height="16" fill="currentColor" class="me-1" viewBox="0 0 16 16" style="display:inline-block;vertical-align:middle;">
                    <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
                </svg>
                Enviar notificación por WhatsApp al paciente
            </label>
        </div>
        <small class="text-muted">Se abrirá WhatsApp con el mensaje listo para enviar</small>
    </div>
    @endif

    <a href="{{ route('oficios.show', $oficio) }}" class="btn btn-secondary">Cancelar</a>
    <button type="submit" class="btn btn-primary">Guardar turno</button>
</form>
@endsection