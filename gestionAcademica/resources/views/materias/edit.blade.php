@extends('adminlte::page')

@section('title', 'Editar Materia')

@section('content_header')
    <h1>Editar Materia</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('materias.update', $materia) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="codigo">Código</label>
                    <input type="text" id="codigo" name="codigo" class="form-control" value="{{ old('codigo', $materia->codigo) }}">
                </div>

                <div class="form-group">
                    <label for="nombre">Nombre de la Materia</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre', $materia->nombre) }}" required>
                </div>

                <div class="form-group">
                    <label for="prerequisites">Correlatividades (Mantén presionado Ctrl para seleccionar varias)</label>
                    @php
                        $prerequisitesIds = $materia->prerequisites->pluck('id')->toArray();
                    @endphp
                    <select id="prerequisites" name="prerequisites[]" class="form-control" multiple>
                        @foreach($materias as $prereq)
                            <option value="{{ $prereq->id }}" {{ in_array($prereq->id, $prerequisitesIds) ? 'selected' : '' }}>
                                {{ $prereq->nombre }} (Código: {{ $prereq->codigo }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="carga_horaria_total">Carga Horaria Total (hs)</label>
                    <input type="number" id="carga_horaria_total" name="carga_horaria_total" class="form-control" value="{{ old('carga_horaria_total', $materia->carga_horaria_total) }}" required>
                </div>

                <div class="form-group">
                    <label for="regimen">Régimen</label>
                    <select id="regimen" name="regimen" class="form-control" required>
                        <option value="Anual" {{ old('regimen', $materia->regimen) == 'Anual' ? 'selected' : '' }}>Anual</option>
                        <option value="Cuatrimestral" {{ old('regimen', $materia->regimen) == 'Cuatrimestral' ? 'selected' : '' }}>Cuatrimestral</option>
                        <option value="Bimestral" {{ old('regimen', $materia->regimen) == 'Bimestral' ? 'selected' : '' }}>Bimestral</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="modulo_id">Módulo al que pertenece</label>
                    <select id="modulo_id" name="modulo_id" class="form-control" required>
                        @foreach($modulos as $modulo)
                            <option value="{{ $modulo->id }}" {{ old('modulo_id', $materia->modulo_id) == $modulo->id ? 'selected' : '' }}>
                                {{ $modulo->nombre }} (Plan: {{ $modulo->planEstudio->nombre ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <input type="hidden" name="plan_id" value="{{ $planId }}">

                <button type="submit" class="btn btn-success">Actualizar Materia</button>
                <a href="{{ route('planes.contenido', $planId) }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@stop