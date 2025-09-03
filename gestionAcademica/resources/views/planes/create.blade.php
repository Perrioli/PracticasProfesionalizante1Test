@extends('adminlte::page')

@section('title', 'Nuevo Plan de Estudio')

@section('content_header')
    <h1>Crear Nuevo Plan de Estudio</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Datos del Nuevo Plan</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('planes-de-estudio.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nombre">Nombre del Plan</label>
                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" placeholder="Ej: Profesorado de Educación Primaria - Plan 2024" required>
                    @error('nombre')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="resolucion">Resolución</label>
                    <input type="text" name="resolucion" class="form-control @error('resolucion') is-invalid @enderror" value="{{ old('resolucion') }}" placeholder="Ej: 13995-E" required>
                    @error('resolucion')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="ano_implementacion">Año de Implementación</label>
                    <input type="number" name="ano_implementacion" class="form-control @error('ano_implementacion') is-invalid @enderror" value="{{ old('ano_implementacion') }}" placeholder="YYYY" required>
                    @error('ano_implementacion')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-success">Guardar Plan</button>
                <a href="{{ route('planes-de-estudio.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@stop