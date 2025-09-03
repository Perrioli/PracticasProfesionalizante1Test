@extends('adminlte::page')

@section('title', 'Horario del Curso')

@section('content_header')
<h1>Gestionar Horario</h1>
<h4>Curso: {{ $curso->nombre }} (Año Lectivo: {{ $curso->ano_ivo }})</h4>
@stop

@section('content')
{{-- Bloque para mostrar mensajes de éxito o error --}}
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif
@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif
{{-- Formulario para añadir nueva asignación --}}
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Añadir Materia al Horario</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('cursos.horario.store', $curso) }}" method="POST">
            @csrf
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label>Materia</label>
                    <select name="materia_id" class="form-control" required>
                        <option value="">-- Seleccionar Materia --</option>
                        @foreach($materiasDisponibles as $materia)
                        <option value="{{ $materia->id }}">{{ $materia->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Docente</label>
                    <select name="docente_id" class="form-control" required>
                        <option value="">-- Seleccionar Docente --</option>
                        @foreach($docentesDisponibles as $docente)
                        <option value="{{ $docente->id }}">{{ $docente->apellido }}, {{ $docente->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Día</label>
                    <select name="dia_semana" class="form-control" required>
                        <option value="Lunes">Lunes</option>
                        <option value="Martes">Martes</option>
                        <option value="Miércoles">Miércoles</option>
                        <option value="Jueves">Jueves</option>
                        <option value="Viernes">Viernes</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Hora Inicio</label>
                    <input type="time" name="hora_inicio" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label>Hora Fin</label>
                    <input type="time" name="hora_fin" class="form-control" required>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Agregar al Horario</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Tabla que muestra el horario actual --}}
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Horario Actual del Curso</h3>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover">
            <thead class="thead-light">
                <tr>
                    <th>Materia</th>
                    <th>Docente</th>
                    <th>Día</th>
                    <th>Horario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($horario as $item)
                <tr>
                    <td>{{ $item->materia->nombre }}</td>
                    <td>{{ $item->docente->apellido }}, {{ $item->docente->nombre }}</td>
                    <td>{{ $item->dia_semana }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->hora_fin)->format('H:i') }}</td>
                    <td>
                        <form action="{{ route('horarios.destroy', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('¿Estás seguro?')">Quitar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">El horario de este curso aún no ha sido configurado.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop