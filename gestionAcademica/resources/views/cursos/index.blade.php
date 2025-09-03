@extends('adminlte::page')

@section('title', 'Gestión de Cursos')

@section('content_header')
    <h1>Gestión de Cursos</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de Cursos</h3>
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

            @forelse ($cursos as $curso)
                <div class="card card-outline card-secondary mb-3">
                    {{-- Fila principal / Encabezado de la tarjeta --}}
                    <div class="card-header d-flex justify-content-between align-items-center">
                        {{-- Información del Curso a la izquierda --}}
                        <div class="flex-grow-1">
                            <strong>{{ $curso->nombre }}</strong> <br>
                            <small class="text-muted">
                                {{ $curso->modulo->planEstudio->nombre ?? 'N/A' }} |
                                Módulo: {{ $curso->modulo->nombre ?? 'N/A' }} |
                                Turno: {{ $curso->turno }} |
                                Año: {{ $curso->ano_lectivo }}
                            </small>
                        </div>

                        {{-- Botones de Acción y Desplegable a la derecha --}}
                        <div class="d-flex align-items-center">
                            <a href="{{ route('cursos.horario', $curso) }}" class="btn btn-sm btn-info mr-1" title="Gestionar Horario">
                                <i class="fas fa-calendar-alt"></i> Horario
                            </a>
                            <a href="{{ route('cursos.edit', $curso) }}" class="btn btn-sm btn-warning mr-1" title="Editar">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <form action="{{ route('cursos.destroy', $curso) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger mr-2" title="Eliminar" onclick="return confirm('¿Estás seguro?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            
                            {{-- Botón para Desplegar --}}
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Ver/Ocultar Horario">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    {{-- Cuerpo desplegable con la sub-tabla del horario --}}
                    <div class="card-body" style="display: none;">
                        <h5>Horario del Curso</h5>
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Materia</th>
                                    <th>Docente</th>
                                    <th>Día</th>
                                    <th>Horario</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($curso->horario as $horarioItem)
                                    <tr>
                                        <td>{{ $horarioItem->materia->nombre ?? 'N/A' }}</td>
                                        <td>{{ $horarioItem->docente->apellido ?? 'N/A' }}, {{ $horarioItem->docente->nombre ?? '' }}</td>
                                        <td>{{ $horarioItem->dia_semana }}</td>
                                        <td>{{ \Carbon\Carbon::parse($horarioItem->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($horarioItem->hora_fin)->format('H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Este curso aún no tiene un horario asignado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">No hay cursos registrados.</div>
            @endforelse
        </div>
    </div>
@stop