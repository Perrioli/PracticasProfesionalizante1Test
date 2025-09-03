@extends('adminlte::page')

@section('title', 'Editar Módulo')

@section('content_header')
    <h1>Editar Módulo</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('modulos.update', $modulo) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="nombre">Nombre del Módulo</label>
                    <input type="text" name="nombre" class="form-control" value="{{ $modulo->nombre }}" required>
                </div>
                <div class="form-group">
                    <label for="plan_estudio_id">Plan de Estudio</label>
                    <select name="plan_estudio_id" class="form-control" required>
                        @foreach($planes as $plan)
                            <option value="{{ $plan->id }}" {{ $modulo->plan_estudio_id == $plan->id ? 'selected' : '' }}>
                                {{ $plan->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="orden">Orden</label>
                    <input type="number" name="orden" class="form-control" value="{{ $modulo->orden }}" required>
                </div>
                <div class="form-group">
                    <label for="ano_correspondiente">Año al que corresponde</label>
                    <input type="number" name="ano_correspondiente" class="form-control" value="{{ $modulo->ano_correspondiente }}" required>
                </div>
                <button type="submit" class="btn btn-success">Actualizar Módulo</button>
                <a href="{{ route('modulos.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@stop