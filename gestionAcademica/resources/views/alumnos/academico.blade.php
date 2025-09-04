@extends('adminlte::page')

@section('title', 'Actualizar Info Académica')

@section('content_header')
    <h1>Actualizar Información Académica</h1>
    <h4>Alumno: {{ $alumno->apellido }}, {{ $alumno->nombre }}</h4>
@stop

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif

    {{-- PASO 1: Formulario para SELECCIONAR MÓDULO --}}
    <div class="card card-info">
        <div class="card-header"><h3 class="card-title">Inscribir a Materias de un Nuevo Módulo</h3></div>
        <div class="card-body">
            <form action="{{ route('alumnos.academico.edit', $alumno) }}" method="GET">
                <div class="row align-items-end">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>1. Seleccionar Plan de Estudio</label>
                            <select id="plan_estudio_select" class="form-control">
                                <option value="">-- Elija un plan --</option>
                                @foreach ($planesDeEstudio as $plan)
                                    <option value="{{ $plan->id }}">{{ $plan->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>2. Seleccionar Módulo</label>
                            <select name="ver_modulo_id" id="modulo_id" class="form-control" required disabled>
                                <option value="">-- Primero elija un plan --</option>
                                @foreach ($modulosDisponibles as $modulo)
                                    <option value="{{ $modulo->id }}" data-plan="{{ $modulo->plan_estudio_id }}" style="display: none;">
                                        Módulo {{ $modulo->orden }} ({{ $modulo->ano_correspondiente }}er Año)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">Ver Materias</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- PASO 2: Tarjeta que aparece para INSCRIBIR en materias --}}
    @if (isset($materiasParaInscribir) && $materiasParaInscribir->isNotEmpty())
    <div class="card card-success">
        <div class="card-header"><h3 class="card-title">Seleccionar Materias para Inscribir del <strong>{{ $materiasParaInscribir->first()->modulo->nombre }}</strong></h3></div>
        <div class="card-body">
            <form action="{{ route('alumnos.materias.enroll') }}" method="POST">
                @csrf
                <input type="hidden" name="alumno_id" value="{{ $alumno->id }}">
                <table class="table table-sm">
                    @foreach ($materiasParaInscribir as $materia)
                        @php
                            $yaInscripto = $materiasData->has($materia->id);
                            $puedeCursar = $alumno->haAprobadoCorrelativas($materia);
                        @endphp
                        <tr>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="materias[]" value="{{ $materia->id }}"
                                        id="materia_{{ $materia->id }}"
                                        {{ $yaInscripto || !$puedeCursar ? 'disabled' : '' }}>
                                    <label class="form-check-label" for="materia_{{ $materia->id }}">
                                        {{ $materia->nombre }}
                                    </label>
                                </div>
                            </td>
                            <td>
                                @if ($yaInscripto)
                                    <span class="badge bg-secondary">Ya inscripto</span>
                                @elseif (!$puedeCursar)
                                    <span class="badge bg-danger">Correlativas pendientes</span>
                                @else
                                    <span class="badge bg-success">Disponible</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
                <button type="submit" class="btn btn-success mt-3">Inscribir en Materias Seleccionadas</button>
            </form>
        </div>
    </div>
    @endif

    {{-- Tabla de Módulos Ya Inscriptos --}}
    @if($modulosCursados->isNotEmpty())
        <hr>
        <h4 class="mb-3">Módulos Inscriptos</h4>
        @foreach ($modulosCursados as $modulo)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <strong>{{ $modulo->nombre }}</strong> - Estado:
                        <span class="badge bg-info">{{ $modulo->pivot->estado }}</span>
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Materia</th>
                                <th style="width: 120px;">Nota Final</th>
                                <th style="width: 180px;">Estado</th>
                                <th>Docente</th>
                                <th style="width: 150px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($modulo->materias as $materia)
                                @php $data = $materiasData->get($materia->id); @endphp
                                @if ($data)
                                    <tr>
                                        <form action="{{ route('alumnos.materia.update', ['alumno' => $alumno, 'materia' => $materia]) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <td>{{ $materia->nombre }}</td>
                                            <td>
                                                <input type="number" name="nota_final" class="form-control form-control-sm" value="{{ $data->pivot->nota_final }}" step="0.01" min="0" max="10">
                                            </td>
                                            <td>
                                                <select name="estado" class="form-control form-control-sm">
                                                    <option value="Cursando" {{ $data->pivot->estado == 'Cursando' ? 'selected' : '' }}>Cursando</option>
                                                    <option value="Aprobada" {{ $data->pivot->estado == 'Aprobada' ? 'selected' : '' }}>Aprobada</option>
                                                    <option value="Previa" {{ $data->pivot->estado == 'Previa' ? 'selected' : '' }}>Previa</option>
                                                    <option value="Libre" {{ $data->pivot->estado == 'Libre' ? 'selected' : '' }}>Libre</option>
                                                </select>
                                            </td>
                                            <td>{{ $data->docentes->first()->apellido ?? 'Sin asignar' }}</td>
                                            <td>
                                                <button type="submit" class="btn btn-sm btn-success" title="Guardar"><i class="fas fa-save"></i></button>
                                                </form> {{-- Se cierra el form de guardar --}}
                                                
                                                {{-- Formulario para quitar materia --}}
                                                <form action="{{ route('alumnos.materia.destroy', ['alumno' => $alumno, 'materia' => $materia]) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Quitar" onclick="return confirm('¿Quitar esta materia del alumno?')"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    @endif
    <a href="{{ route('alumnos.perfil', $alumno) }}" class="btn btn-secondary mt-3"><i class="fas fa-arrow-left"></i> Volver al Perfil</a>
@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const planSelect = document.getElementById('plan_estudio_select');
        const moduloSelect = document.getElementById('modulo_id');
        planSelect.addEventListener('change', function () {
            const selectedPlanId = this.value;
            moduloSelect.value = '';
            moduloSelect.disabled = true;
            for (let i = 0; i < moduloSelect.options.length; i++) {
                const option = moduloSelect.options[i];
                if (!option.dataset.plan) {
                    option.style.display = 'block'; continue;
                }
                if (option.dataset.plan === selectedPlanId) {
                    option.style.display = 'block';
                    moduloSelect.disabled = false;
                } else {
                    option.style.display = 'none';
                }
            }
        });
    });
</script>
@stop