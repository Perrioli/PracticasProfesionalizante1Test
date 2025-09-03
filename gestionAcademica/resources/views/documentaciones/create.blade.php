@extends('adminlte::page')

@section('title', 'Registrar Documento')

@section('content_header')
    {{-- Título dinámico que muestra el nombre del alumno y el tipo de documento --}}
    <h1>Registrar: {{ $tipoDoc }}</h1>
    <p>Para el alumno: {{ $alumno->apellido }}, {{ $alumno->nombre }}</p>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            {{-- ¡IMPORTANTE! enctype es necesario para subir archivos --}}
            <form action="{{ route('documentaciones.store', $alumno) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                {{-- Campo oculto para enviar el tipo de documento --}}
                <input type="hidden" name="tipo_documento" value="{{ $tipoDoc }}">

                <div class="form-group">
                    <label for="fecha_presentacion">Fecha de Presentación</label>
                    <input type="date" name="fecha_presentacion" class="form-control @error('fecha_presentacion') is-invalid @enderror" value="{{ old('fecha_presentacion', now()->toDateString()) }}">
                    @error('fecha_presentacion')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="estado">Estado</label>
                    <select name="estado" class="form-control @error('estado') is-invalid @enderror">
                        <option value="Entregado" {{ old('estado') == 'Entregado' ? 'selected' : '' }}>Entregado</option>
                        <option value="Pendiente" {{ old('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                    </select>
                    @error('estado')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="archivo">Seleccionar Archivo</label>
                    <input type="file" name="archivo" class="form-control-file @error('archivo') is-invalid @enderror">
                    @error('archivo')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                    <small class="form-text text-muted">Archivos permitidos: PDF, JPG, PNG. Tamaño máximo: 2MB.</small>
                </div>

                <button type="submit" class="btn btn-success">Guardar y Subir Documento</button>
                <a href="{{ route('alumnos.documentacion', $alumno) }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@stop