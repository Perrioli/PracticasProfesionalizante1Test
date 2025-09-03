@extends('adminlte::page')

@section('title', 'Nueva Materia')

@section('content_header')
<h1>Crear Nueva Materia</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('materias.store') }}" method="POST">
            @csrf
            @if(isset($planId))
            <input type="hidden" name="plan_id" value="{{ $planId }}">
            @endif
            <div class="form-group">
                <label for="codigo">Código</label>
                <input type="text" id="codigo" name="codigo" class="form-control" value="{{ old('codigo') }}">
            </div>

            <div class="form-group">
                <label for="nombre">Nombre de la Materia</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
            </div>

            <div class="form-group">
                <label for="prerequisites">Correlatividades (Mantén presionado Ctrl para seleccionar varias)</label>
                <select id="prerequisites" name="prerequisites[]" class="form-control" multiple>
                    @foreach($materias as $prereq)
                    <option value="{{ $prereq->id }}">{{ $prereq->nombre }} (Código: {{ $prereq->codigo }})</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="carga_horaria_total">Carga Horaria Total (hs)</label>
                <input type="number" id="carga_horaria_total" name="carga_horaria_total" class="form-control" value="{{ old('carga_horaria_total') }}" required>
            </div>

            <div class="form-group">
                <label for="regimen">Régimen</label>
                <select id="regimen" name="regimen" class="form-control" required>
                    <option value="Anual" {{ old('regimen') == 'Anual' ? 'selected' : '' }}>Anual</option>
                    <option value="Cuatrimestral" {{ old('regimen') == 'Cuatrimestral' ? 'selected' : '' }}>Cuatrimestral</option>
                    <option value="Bimestral" {{ old('regimen') == 'Bimestral' ? 'selected' : '' }}>Bimestral</option>
                </select>
            </div>

            <div class="form-group">
                <label for="modulo_id">Módulo al que pertenece</label>
                <select id="modulo_id" name="modulo_id" class="form-control" required>
                    <option value="">-- Seleccione un Módulo --</option>
                    @foreach($modulos as $modulo)
                    <option value="{{ $modulo->id }}" {{ (isset($moduloIdSeleccionado) && $moduloIdSeleccionado == $modulo->id) ? 'selected' : '' }}>
                        {{ $modulo->nombre }} (Plan: {{ $modulo->planEstudio->nombre ?? 'N/A' }})
                    </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-success">Guardar Materia</button>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@stop