@extends('adminlte::page')
@section('title', 'Nueva Submateria')
@section('content_header') <h1>Crear Nueva Submateria</h1> @stop
@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('submaterias.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Carga Horaria</label>
                <input type="number" name="carga_horaria" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Asignar a Materia(s) (Ctrl + clic para seleccionar varias)</label>
                <select name="materias[]" class="form-control" multiple required>
                    @foreach($materias as $materia)
                        <option value="{{ $materia->id }}">{{ $materia->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-success">Guardar</button>
            <a href="{{ route('submaterias.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@stop