@extends('layouts.app')
@section('content')
<h2 class="mb-4">Editar oficio</h2>
<form action="{{ route('oficios.update', $oficio) }}" method="POST">
    @csrf @method('PUT')
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Número de oficio</label>
            <input type="text" name="numero_oficio" class="form-control @error('numero_oficio') is-invalid @enderror" value="{{ old('numero_oficio', $oficio->numero_oficio) }}">
            @error('numero_oficio')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Fecha de recepción</label>
            <input type="date" name="fecha_recepcion" class="form-control" value="{{ old('fecha_recepcion', $oficio->fecha_recepcion) }}">
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Paciente</label>
            <select name="paciente_id" class="form-select">
                @foreach($pacientes as $paciente)
                <option value="{{ $paciente->id }}" {{ $oficio->paciente_id == $paciente->id ? 'selected' : '' }}>
                    {{ $paciente->apellido }}, {{ $paciente->nombre }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Juzgado</label>
            <select name="juzgado_id" class="form-select">
                @foreach($juzgados as $juzgado)
                <option value="{{ $juzgado->id }}" {{ $oficio->juzgado_id == $juzgado->id ? 'selected' : '' }}>
                    {{ $juzgado->nombre }}
                </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Medio de recepción</label>
            <select name="medio_recepcion" class="form-select">
                <option value="papel" {{ $oficio->medio_recepcion == 'papel' ? 'selected' : '' }}>Papel</option>
                <option value="email" {{ $oficio->medio_recepcion == 'email' ? 'selected' : '' }}>Email</option>
                <option value="whatsapp" {{ $oficio->medio_recepcion == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Tipo de pedido</label>
            <input type="text" name="tipo_pedido" class="form-control" value="{{ old('tipo_pedido', $oficio->tipo_pedido) }}">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Estado</label>
            <select name="estado" class="form-select">
                <option value="pendiente" {{ $oficio->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="en_curso" {{ $oficio->estado == 'en_curso' ? 'selected' : '' }}>En curso</option>
                <option value="cerrado" {{ $oficio->estado == 'cerrado' ? 'selected' : '' }}>Cerrado</option>
            </select>
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Observaciones</label>
        <textarea name="observaciones" class="form-control" rows="3">{{ old('observaciones', $oficio->observaciones) }}</textarea>
    </div>
    <a href="{{ route('oficios.index') }}" class="btn btn-secondary">Cancelar</a>
    <button type="submit" class="btn btn-primary">Actualizar</button>
</form>
@endsection