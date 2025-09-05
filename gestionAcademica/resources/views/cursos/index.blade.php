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
                                    @php
                                        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
                                        $horas = [];

                                        // LÓGICA PARA DEFINIR HORAS SEGÚN EL TURNO
                                        if ($curso->turno == 'Mañana') {
                                            $horas = [
                                                '1' => '08:00 - 08:40', '2' => '08:40 - 09:20',
                                                '3' => '09:20 - 10:00', '4' => '10:20 - 11:00',
                                                '5' => '11:00 - 11:40', '6' => '11:40 - 12:20',
                                                '7' => '12:20 - 13:00',
                                            ];
                                        } elseif ($curso->turno == 'Noche') {
                                            $horas = [
                                                '1' => '18:00 - 18:40', '2' => '18:40 - 19:20',
                                                '3' => '19:20 - 20:00', '4' => '20:20 - 21:00',
                                                '5' => '21:00 - 21:40', '6' => '21:40 - 22:20',
                                            ];
                                        } else { // Turno Tarde por defecto
                                            $horas = [
                                                '1' => '13:20 - 14:00', '2' => '14:00 - 14:40',
                                                '3' => '14:40 - 15:20', '4' => '15:20 - 16:00',
                                                '5' => '16:00 - 16:40', '6' => '16:40 - 17:20',
                                                '7' => '17:20 - 18:00',
                                            ];
                                        }
                                        
                                        $horarioOrganizado = $curso->horario->groupBy('dia_semana');
                                    @endphp

                                    <table class="table table-bordered text-center">
                                        <thead class="thead-light">
                                            <tr>
                                                <th style="width: 12%;">Horas</th>
                                                @foreach ($dias as $dia)
                                                    <th>{{ $dia }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($horas as $num => $rango)
                                                <tr>
                                                    <td><strong>{{ $num }}°</strong><br><small>{{ $rango }}</small></td>
                                                    @foreach ($dias as $dia)
                                                        @php
                                                            $horaInicioSlot = \Carbon\Carbon::parse(trim(explode('-', $rango)[0]));
                                                            $clase = ($horarioOrganizado[$dia] ?? collect())->first(function ($item) use ($horaInicioSlot) {
                                                                $horaInicioClase = \Carbon\Carbon::parse($item->hora_inicio);
                                                                $horaFinClase = \Carbon\Carbon::parse($item->hora_fin);
                                                                return $horaInicioSlot->between($horaInicioClase, $horaFinClase, false);
                                                            });
                                                        @endphp
                                                        <td class="{{ $clase ? 'p-1 align-middle' : '' }}">
                                                            @if ($clase)
                                                                <div class="bg-light p-2 rounded" style="font-size: 0.85rem;">
                                                                    <strong>{{ $clase->materia->nombre ?? 'N/A' }}</strong><br>
                                                                    <small>{{ $clase->docente->apellido ?? 'N/A' }}</small>
                                                                </div>
                                                            @endif
                                                        </td>
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