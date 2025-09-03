@extends('adminlte::page')

@section('title', 'Editar Plan de Estudio')

@section('content_header')
    <h1>Editar Plan de Estudio</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('planes-de-estudio.update', $planEstudio) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="nombre">Nombre del Plan</label>
                    <input type="text" name="nombre" class="form-control" value="{{ $planEstudio->nombre }}" required>
                </div>
                <div class="form-group">
                    <label for="resolucion">Resolución</label>
                    <input type="text" name="resolucion" class="form-control" value="{{ $planEstudio->resolucion }}" required>
                </div>
                <div class="form-group">
                    <label for="ano_implementacion">Año de Implementación</label>
                    <input type="number" name="ano_implementacion" class="form-control" value="{{ $planEstudio->ano_implementacion }}" required>
                </div>
                <button type="submit" class="btn btn-success">Actualizar Plan</button>
                <a href="{{ route('planes-de-estudio.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@stop