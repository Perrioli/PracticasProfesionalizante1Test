@extends('adminlte::page')

@section('title', 'Nuevo Curso')

@section('content_header')
    <h1>Crear Nuevo Curso</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('cursos.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nombre">Nombre del Curso (Ej: 1er Año A)</label>
                <input type="text" id="nombre" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required>
                @error('nombre')
                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label for="turno">Turno</label>
                <select id="turno" name="turno" class="form-control" required>
                    <option value="Mañana">Mañana</option>
                    <option value="Tarde">Tarde</option>
                    <option value="Noche">Noche</option>
                </select>
            </div>
            <div class="form-group">
                <label for="ano_lectivo">Año Lectivo</label>
                <input type="number" id="ano_lectivo" name="ano_lectivo" class="form-control" placeholder="YYYY" value="{{ date('Y') }}" required>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="plan_estudio_select">1. Seleccionar Plan de Estudio</label>
                        <select id="plan_estudio_select" class="form-control" required>
                            <option value="">-- Elija un plan --</option>
                            @foreach ($planes as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="modulo_id">2. Seleccionar Módulo</label>
                        <select name="modulo_id" id="modulo_id" class="form-control @error('modulo_id') is-invalid @enderror" required disabled>
                            <option value="">-- Primero elija un plan --</option>
                            @foreach ($modulos as $modulo)
                                <option value="{{ $modulo->id }}" data-plan="{{ $modulo->plan_estudio_id }}" style="display: none;">
                                    Módulo {{ $modulo->orden }} ({{ $modulo->ano_correspondiente }}er Año)
                                </option>
                            @endforeach
                        </select>
                        @error('modulo_id')
                            <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Guardar Curso</button>
            <a href="{{ route('cursos.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
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