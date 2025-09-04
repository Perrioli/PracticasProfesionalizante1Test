@extends('adminlte::page')

@section('title', 'Planes de Estudio')

@section('content_header')
<h1>Planes de Estudio</h1>
@stop

@section('content')
@if (session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif
<div class="mb-3">
    <a href="{{ route('planes-de-estudio.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Agregar Plan de Estudio
    </a>
    <a href="{{ route('modulos.index') }}" class="btn btn-secondary">
        <i class="fas fa-layer-group"></i> Gestionar Módulos
    </a>
</div>

@forelse ($planes as $plan)
<div class="card card-outline card-info">
    <div class="card-header">
        <h3 class="card-title"><strong>{{ $plan->nombre }}</strong></h3>
        <div class="card-tools">
            <a href="{{ route('planes.contenido', $plan) }}" class="btn btn-sm btn-info" title="Gestionar Contenido">
                <i class="fas fa-sitemap"></i>
            </a>
            <a href="{{ route('planes-de-estudio.edit', $plan) }}" class="btn btn-sm btn-warning" title="Editar Plan">
                <i class="fas fa-edit"></i>
            </a>
            <form action="{{ route('planes-de-estudio.destroy', $plan) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar Plan" onclick="return confirm('¿Estás seguro?')">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
            {{-- Este botón controla el colapso del card-body --}}
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    {{-- El card-body DEBE estar dentro del div.card --}}
    <div class="card-body p-0">
        @foreach ($plan->modulos->sortBy('orden') as $modulo)
        <div class="p-3">
            <h4>{{ $modulo->nombre }} ({{ $modulo->ano_correspondiente }}er Año)</h4>
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Código</th>
                        <th>Asignatura</th>
                        <th>Correlatividad</th>
                        <th>Horas</th>
                        <th>Régimen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($modulo->materias as $materia)
                    <tr>
                        <td>{{ $materia->codigo }}</td>
                        <td>{{ $materia->nombre }}</td>
                        <td>
                            @forelse ($materia->prerequisites as $prereq)
                            <span class="badge bg-secondary">{{ $prereq->codigo }}</span>
                            @empty
                            ---
                            @endforelse
                        </td>
                        <td>{{ $materia->carga_horaria_total }}</td>
                        <td>{{ $materia->regimen }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endforeach
    </div>
</div> {{-- El div.card se cierra AQUI --}}
@empty
<div class="alert alert-warning">No hay planes de estudio registrados.</div>
@endforelse
@stop