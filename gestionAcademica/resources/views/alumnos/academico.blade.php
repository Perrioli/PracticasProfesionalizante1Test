@extends('adminlte::page')

@section('title', 'Actualizar Info Académica')

@section('content_header')
<h1>Actualizar Información Académica</h1>
<h4>Alumno: {{ $alumno->apellido }}, {{ $alumno->nombre }}</h4>
@stop

@section('content')
{{-- Todo el contenido de la página, incluyendo el formulario de inscripción, va aquí dentro --}}

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- Tarjeta para Inscribir a Carrera / Plan de estudio --}}
<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">Inscribir a Carrera / Plan de estudio</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('alumnos.academico.enroll', $alumno) }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="plan_estudio_select">1. Seleccionar Plan de Estudio</label>
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
                        <label for="modulo_id">2. Seleccionar Módulo</label>
                        <select name="modulo_id" id="modulo_id" class="form-control" required disabled>
                            <option value="">-- Primero elija un plan --</option>
                            @foreach ($modulosDisponibles as $modulo)
                            <option value="{{ $modulo->id }}" data-plan="{{ $modulo->plan_estudio_id }}" style="display: none;">
                                Módulo {{ $modulo->orden }} ({{ $modulo->ano_correspondiente }}er Año)
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2 align-self-end">
                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-block">Inscribir</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Mostramos los módulos que el alumno ya está cursando --}}
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
                    <th style="width: 100px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($modulo->materias as $materia)
                @php $data = $materiasData->get($materia->id); @endphp
                @if ($data)
                {{-- Cada fila es un formulario que apunta a la nueva ruta de actualización --}}
                <form action="{{ route('alumnos.materia.update', ['alumno' => $alumno, 'materia' => $materia]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <tr>
                        <td>{{ $materia->nombre }}</td>
                        <td>
                            <input type="number" name="nota_final" class="form-control form-control-sm"
                                value="{{ $data->pivot->nota_final }}" step="0.01" min="0" max="10">
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
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="fas fa-save"></i> Guardar
                            </button>
                        </td>
                    </tr>
                </form>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endforeach
@endif

@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const planSelect = document.getElementById('plan_estudio_select');
        const moduloSelect = document.getElementById('modulo_id');

        planSelect.addEventListener('change', function() {
            const selectedPlanId = this.value;

            moduloSelect.value = '';
            moduloSelect.disabled = true;

            for (let i = 0; i < moduloSelect.options.length; i++) {
                const option = moduloSelect.options[i];

                if (!option.dataset.plan) {
                    option.style.display = 'block';
                    continue;
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