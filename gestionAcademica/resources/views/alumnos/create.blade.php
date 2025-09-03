@extends('adminlte::page')

@section('title', 'Nuevo Alumno')

@section('content_header')
    <h1>Registrar Nuevo Alumno</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('alumnos.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" placeholder="Ingrese el nombre del alumno">
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
                            <input type="text" name="apellido" class="form-control @error('apellido') is-invalid @enderror" value="{{ old('apellido') }}" placeholder="Ingrese el apellido del alumno">
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
                    <input type="text" name="dni" class="form-control @error('dni') is-invalid @enderror" value="{{ old('dni') }}" placeholder="Ingrese el DNI sin puntos">
                     @error('dni')
                        <span class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" class="form-control @error('fecha_nacimiento') is-invalid @enderror" value="{{ old('fecha_nacimiento') }}">
                     @error('fecha_nacimiento')
                        <span class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="escuela_origen">Escuela de Origen</label>
                    <input type="text" name="escuela_origen" class="form-control @error('escuela_origen') is-invalid @enderror" value="{{ old('escuela_origen') }}" placeholder="Nombre de la escuela secundaria">
                     @error('escuela_origen')
                        <span class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                 <div class="form-group">
                    <label for="ano_ingreso">Año de Ingreso</label>
                    <input type="number" name="ano_ingreso" class="form-control @error('ano_ingreso') is-invalid @enderror" value="{{ old('ano_ingreso') }}" placeholder="YYYY">
                     @error('ano_ingreso')
                        <span class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success">Guardar Alumno</button>
                <a href="{{ route('alumnos.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@stop