@extends('layouts.app')
@section('content')
<h2 class="mb-4">Editar informe</h2>
<form action="{{ route('informes.update', $informe) }}" method="POST">
    @csrf @method('PUT')
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Profesional</label>
            <select name="profesional_id" class="form-select">
                @foreach($profesionales as $profesional)
                <option value="{{ $profesional->id }}" {{ $informe->profesional_id == $profesional->id ? 'selected' : '' }}>
                    {{ $profesional->apellido }}, {{ $profesional->nombre }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Fecha del informe</label>
            <input type="date" name="fecha_informe" class="form-control" value="{{ old('fecha_informe', $informe->fecha_informe) }}">
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Contenido</label>
        <textarea name="contenido" class="form-control" rows="6">{{ old('contenido', $informe->contenido) }}</textarea>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">¿Enviado al juzgado?</label>
            <select name="enviado_juzgado" class="form-select">
                <option value="0" {{ !$informe->enviado_juzgado ? 'selected' : '' }}>No</option>
                <option value="1" {{ $informe->enviado_juzgado ? 'selected' : '' }}>Sí</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Fecha de envío</label>
            <input type="date" name="fecha_envio" class="form-control" value="{{ old('fecha_envio', $informe->fecha_envio) }}">
        </div>
    </div>
    <a href="{{ route('oficios.show', $informe->oficio_id) }}" class="btn btn-secondary">Cancelar</a>
    <button type="submit" class="btn btn-primary">Actualizar</button>
</form>
@endsection