@extends('layouts.app')

@section('title', 'Nuevo Alumno')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Registrar Nuevo Alumno</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('alumnos.store') }}" method="POST">
                @csrf <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}">
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="apellido">Apellido</label>
                            <input type="text" name="apellido" id="apellido" class="form-control @error('apellido') is-invalid @enderror" value="{{ old('apellido') }}">
                            @error('apellido')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="dni">DNI</label>
                    <input type="text" name="dni" id="dni" class="form-control @error('dni') is-invalid @enderror" value="{{ old('dni') }}">
                    @error('dni')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control @error('fecha_nacimiento') is-invalid @enderror" value="{{ old('fecha_nacimiento') }}">
                    @error('fecha_nacimiento')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="escuela_origen">Escuela de Origen</label>
                    <input type="text" name="escuela_origen" id="escuela_origen" class="form-control @error('escuela_origen') is-invalid @enderror" value="{{ old('escuela_origen') }}">
                    @error('escuela_origen')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                 <div class="form-group">
                    <label for="ano_ingreso">Año de Ingreso</label>
                    <input type="number" name="ano_ingreso" id="ano_ingreso" class="form-control @error('ano_ingreso') is-invalid @enderror" value="{{ old('ano_ingreso') }}" placeholder="YYYY">
                    @error('ano_ingreso')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">Guardar Alumno</button>
                    <a href="{{ route('alumnos.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection