@extends('adminlte::page')

@section('title', 'Editar Alumno')

@section('content_header')
    <h1>Editar Alumno</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('alumnos.update', $alumno) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $alumno->nombre) }}">
                            @error('nombre')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="apellido">Apellido</label>
                            <input type="text" name="apellido" class="form-control @error('apellido') is-invalid @enderror" value="{{ old('apellido', $alumno->apellido) }}">
                             @error('apellido')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="dni">DNI</label>
                    <input type="text" name="dni" class="form-control @error('dni') is-invalid @enderror" value="{{ old('dni', $alumno->dni) }}">
                     @error('dni')
                        <span class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" class="form-control @error('fecha_nacimiento') is-invalid @enderror" value="{{ old('fecha_nacimiento', $alumno->fecha_nacimiento) }}">
                     @error('fecha_nacimiento')
                        <span class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="escuela_origen">Escuela de Origen</label>
                    <input type="text" name="escuela_origen" class="form-control @error('escuela_origen') is-invalid @enderror" value="{{ old('escuela_origen', $alumno->escuela_origen) }}">
                     @error('escuela_origen')
                        <span class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                 <div class="form-group">
                    <label for="ano_ingreso">Año de Ingreso</label>
                    <input type="number" name="ano_ingreso" class="form-control @error('ano_ingreso') is-invalid @enderror" value="{{ old('ano_ingreso', $alumno->ano_ingreso) }}">
                     @error('ano_ingreso')
                        <span class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success">Actualizar Alumno</button>
                <a href="{{ route('alumnos.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@stop