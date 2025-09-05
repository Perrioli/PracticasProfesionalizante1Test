@extends('adminlte::page')

@section('title', 'Gestión de Cursos')

@section('content_header')
    <h1>Gestión de Cursos</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de Cursos por Plan de Estudio</h3>
            <div class="card-tools">
                <a href="{{ route('cursos.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Curso
                </a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @forelse ($planesDeEstudio as $plan)
                {{-- 1. Añadimos la clase 'collapsed-card' aquí --}}
                <div class="card card-outline card-info mb-3 collapsed-card">
                    <div class="card-header">
                        <h3 class="card-title"><strong>{{ $plan->nombre }}</strong></h3>
                        <div class="card-tools">
                            {{-- 2. Cambiamos el ícono a 'fa-plus' para que coincida con el estado colapsado --}}
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Ver/Ocultar Cursos">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @forelse ($plan->modulos->flatMap->cursos as $curso)
                            {{-- 3. Añadimos también 'collapsed-card' aquí para los cursos anidados --}}
                            <div class="card card-outline card-secondary mb-2 collapsed-card">
                                <div class="card-header d-flex align-items-center">
                                    <div class="mr-auto">
                                        <strong>{{ $curso->nombre }}</strong><br>
                                        <small class="text-muted">Módulo: {{ $curso->modulo->nombre ?? 'N/A' }} | Turno: {{ $curso->turno }} | Año: {{ $curso->ano_lectivo }}</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <a href="{{ route('cursos.horario', $curso) }}" class="btn btn-sm btn-info mr-1" title="Gestionar Horario"><i class="fas fa-calendar-alt"></i></a>
                                        <a href="{{ route('cursos.edit', $curso) }}" class="btn btn-sm btn-warning mr-1" title="Editar"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('cursos.destroy', $curso) }}" method="POST" class="d-inline mr-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('¿Estás seguro?')"><i class="fas fa-trash"></i></button>
                                        </form>
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Ver/Ocultar Horario">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body" style="display: none;">
                                    <table class="table table-sm">
                                        <thead><tr><th>Materia</th><th>Docente</th><th>Día</th><th>Horario</th></tr></thead>
                                        <tbody>
                                            @forelse ($curso->horario as $horarioItem)
                                                <tr>
                                                    <td>{{ $horarioItem->materia->nombre ?? 'N/A' }}</td>
                                                    <td>{{ $horarioItem->docente->apellido ?? 'N/A' }}, {{ $horarioItem->docente->nombre ?? '' }}</td>
                                                    <td>{{ $horarioItem->dia_semana }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($horarioItem->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($horarioItem->hora_fin)->format('H:i') }}</td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="4" class="text-muted text-center">No hay horario asignado para este curso.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted p-2">No hay cursos registrados para este plan de estudio.</p>
                        @endforelse
                    </div>
                </div>
            @empty
                <div class="alert alert-warning">No hay planes de estudio registrados.</div>
            @endforelse
        </div>
    </div>
@stop