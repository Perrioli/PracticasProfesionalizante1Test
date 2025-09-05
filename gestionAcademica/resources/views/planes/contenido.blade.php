@extends('adminlte::page')

@section('title', 'Gestionar Contenido del Plan')

@section('content_header')
<h1>Gestionar Contenido del Plan</h1>
<h4>Plan de Estudio: <strong>{{ $planEstudio->nombre }}</strong></h4>
@stop

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center">
        <h3 class="card-title mb-0 mr-auto">Módulos y Materias</h3>
        <a href="{{ route('modulos.create', ['plan_id' => $planEstudio->id]) }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Agregar Módulo
        </a>
    </div>
    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @forelse ($planEstudio->modulos->sortBy('orden') as $modulo)
        {{-- TARJETA DESPLEGABLE --}}
        <div class="card card-outline card-secondary mb-4 collapsed-card">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 mr-auto">
                    <strong>{{ $modulo->nombre }}</strong> ({{ $modulo->ano_correspondiente }}er Año)
                </h4>
                <div class="card-tools d-flex align-items-center">
                    <a href="{{ route('materias.create', ['modulo_id' => $modulo->id]) }}" class="btn btn-sm btn-success mr-2">
                        <i class="fas fa-plus"></i> Agregar Materia
                    </a>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Asignatura</th>
                            <th>Régimen</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($modulo->materias as $materia)
                        <tr>
                            <td>{{ $materia->codigo }}</td>
                            <td>{{ $materia->nombre }}</td>
                            <td>{{ $materia->regimen }}</td>
                            <td>
                                <a href="{{ route('materias.edit', $materia) }}" class="btn btn-xs btn-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('materias.destroy', $materia) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-danger" title="Eliminar" onclick="return confirm('¿Estás seguro?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">No hay materias en este módulo.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @empty
        <div class="alert alert-info">Este plan de estudio aún no tiene módulos.</div>
        @endforelse
    </div>
</div>
<a href="{{ route('planes-de-estudio.index') }}" class="btn btn-secondary mt-3">
    <i class="fas fa-arrow-left"></i> Volver
</a>
@stop