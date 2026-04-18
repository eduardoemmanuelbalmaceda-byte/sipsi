@extends('layouts.app')
@section('content')
<h2 class="mb-4">Nuevo oficio</h2>
<form action="{{ route('oficios.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Número de oficio</label>
            <input type="text" name="numero_oficio" class="form-control @error('numero_oficio') is-invalid @enderror" value="{{ old('numero_oficio') }}">
            @error('numero_oficio')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Fecha de recepción</label>
            <input type="date" name="fecha_recepcion" class="form-control @error('fecha_recepcion') is-invalid @enderror" value="{{ old('fecha_recepcion') }}">
            @error('fecha_recepcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Paciente</label>
            <select name="paciente_id" class="form-select @error('paciente_id') is-invalid @enderror">
                <option value="">Seleccionar paciente...</option>
                @foreach($pacientes as $paciente)
                <option value="{{ $paciente->id }}" {{ old('paciente_id') == $paciente->id ? 'selected' : '' }}>
                    {{ $paciente->apellido }}, {{ $paciente->nombre }} — {{ $paciente->dni }}
                </option>
                @endforeach
            </select>
            @error('paciente_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Juzgado</label>
            <select name="juzgado_id" class="form-select @error('juzgado_id') is-invalid @enderror">
                <option value="">Seleccionar juzgado...</option>
                @foreach($juzgados as $juzgado)
                <option value="{{ $juzgado->id }}" {{ old('juzgado_id') == $juzgado->id ? 'selected' : '' }}>
                    {{ $juzgado->nombre }}
                </option>
                @endforeach
            </select>
            @error('juzgado_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Medio de recepción</label>
            <select name="medio_recepcion" class="form-select @error('medio_recepcion') is-invalid @enderror">
                <option value="">Seleccionar...</option>
                <option value="papel" {{ old('medio_recepcion') == 'papel' ? 'selected' : '' }}>Papel</option>
                <option value="email" {{ old('medio_recepcion') == 'email' ? 'selected' : '' }}>Email</option>
                <option value="whatsapp" {{ old('medio_recepcion') == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
            </select>
            @error('medio_recepcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Tipo de pedido</label>
            <input type="text" name="tipo_pedido" class="form-control @error('tipo_pedido') is-invalid @enderror" value="{{ old('tipo_pedido') }}" placeholder="Ej: Mediación, Informe, Intervención...">
            @error('tipo_pedido')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Observaciones</label>
        <textarea name="observaciones" class="form-control" rows="3">{{ old('observaciones') }}</textarea>
    </div>
    <a href="{{ route('oficios.index') }}" class="btn btn-secondary">Cancelar</a>
    <button type="submit" class="btn btn-primary">Guardar</button>
</form>
@endsection