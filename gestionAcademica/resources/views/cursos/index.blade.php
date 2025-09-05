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
                <div class="card card-outline card-info mb-3 collapsed-card">
                    <div class="card-header">
                        <h3 class="card-title"><strong>{{ $plan->nombre }}</strong></h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Ver/Ocultar Cursos">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @forelse ($plan->modulos->flatMap->cursos as $curso)
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
                                    {{-- INICIO DE LA NUEVA LÓGICA DE HORARIO --}}
                                    @php
                                        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
                                        $horas = [];
                                        if ($curso->turno == 'Mañana') {
                                            $horas = ['08:00', '08:40', '09:20', '10:00', '10:40', '11:20', '12:00'];
                                        } elseif ($curso->turno == 'Noche') {
                                            $horas = ['18:00', '18:40', '19:20', '20:00', '20:40', '21:20'];
                                        } else { // Tarde
                                            $horas = ['13:20', '14:00', '14:40', '15:20', '16:00', '16:40', '17:20'];
                                        }

                                        $scheduleGrid = [];
                                        foreach ($curso->horario as $item) {
                                            $start = \Carbon\Carbon::parse($item->hora_inicio);
                                            $end = \Carbon\Carbon::parse($item->hora_fin);
                                            $durationInSlots = $start->diffInMinutes($end) / 40; // Asumiendo slots de 40 min
                                            
                                            $scheduleGrid[$item->dia_semana][$start->format('H:i')] = [
                                                'materia' => $item->materia,
                                                'docente' => $item->docente,
                                                'rowspan' => $durationInSlots
                                            ];

                                            // Marcamos las celdas que serán ocupadas por el rowspan
                                            for ($i = 1; $i < $durationInSlots; $i++) {
                                                $nextSlot = $start->addMinutes(40)->format('H:i');
                                                $scheduleGrid[$item->dia_semana][$nextSlot] = 'occupied';
                                            }
                                        }
                                    @endphp

                                    <table class="table table-bordered text-center">
                                        <thead class="thead-light">
                                            <tr>
                                                <th style="width: 10%;">Hora</th>
                                                @foreach ($dias as $dia)
                                                    <th>{{ $dia }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($horas as $hora)
                                                <tr>
                                                    <td>{{ $hora }}</td>
                                                    @foreach ($dias as $dia)
                                                        @php
                                                            $cellData = $scheduleGrid[$dia][$hora] ?? null;
                                                        @endphp
                                                        @if ($cellData === 'occupied')
                                                            {{-- No renderizar celda, ya fue ocupada por rowspan --}}
                                                        @elseif (is_array($cellData))
                                                            <td rowspan="{{ $cellData['rowspan'] }}" class="p-1 align-middle bg-light">
                                                                <strong>{{ $cellData['materia']->nombre ?? 'N/A' }}</strong><br>
                                                                <small>{{ $cellData['docente']->apellido ?? 'N/A' }}</small>
                                                            </td>
                                                        @else
                                                            <td></td> {{-- Celda vacía --}}
                                                        @endif
                                                    @endforeach
                                                </tr>
                                            @endforeach
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