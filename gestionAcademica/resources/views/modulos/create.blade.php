@extends('adminlte::page')

@section('title', 'Nuevo Módulo')

@section('content_header')
<h1>Crear Nuevo Módulo</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('modulos.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nombre">Nombre del Módulo</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="plan_estudio_id">Plan de Estudio</label>
                <select name="plan_estudio_id" class="form-control" required>
                    <option value="">-- Seleccione un Plan --</option>
                    @foreach($planes as $plan)
                    <option value="{{ $plan->id }}" {{ (isset($planIdSeleccionado) && $planIdSeleccionado == $plan->id) ? 'selected' : '' }}>
                        {{ $plan->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="orden">Orden (Ej: 1, 2, 3)</label>
                <input type="number" name="orden" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="ano_correspondiente">Año al que corresponde (Ej: 1, 2, 3)</label>
                <input type="number" name="ano_correspondiente" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Guardar Módulo</button>
            <a href="{{ route('modulos.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@stop