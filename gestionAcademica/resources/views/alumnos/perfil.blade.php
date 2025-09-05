@extends('adminlte::page')

@section('title', 'Perfil del Alumno')

@section('content_header')
<h1>Perfil del Alumno</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-4">
        {{-- Tarjeta de Perfil e Información Personal --}}
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    @if ($fotoPerfil && Storage::disk('public')->exists($fotoPerfil->file_path))
                    <img class="profile-user-img img-fluid img-circle"
                        src="{{ asset('storage/' . $fotoPerfil->file_path) }}"
                        alt="Foto de perfil del alumno">
                    @else
                    {{-- Imagen placeholder si no hay foto --}}
                    <img class="profile-user-img img-fluid img-circle"
                        src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png"
                        alt="Foto de perfil no encontrada">
                    @endif
                </div>

                <h3 class="profile-username text-center">{{ $alumno->nombre }} {{ $alumno->apellido }}</h3>
                <p class="text-muted text-center">DNI: {{ $alumno->dni }}</p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Fecha de Nacimiento</b> <a class="float-right">{{ \Carbon\Carbon::parse($alumno->fecha_nacimiento)->format('d/m/Y') }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Año de Ingreso</b> <a class="float-right">{{ $alumno->ano_ingreso }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Escuela de Origen</b> <a class="float-right">{{ $alumno->escuela_origen }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Carrera</b> <a class="float-right">{{ $carrera->nombre ?? 'No asignada' }}</a>
                    </li>
                </ul>

                <a href="{{ route('alumnos.documentacion', $alumno) }}" class="btn btn-primary btn-block"><b>Ver Documentación</b></a>
                <a href="{{ route('alumnos.academico.edit', $alumno) }}" class="btn btn-info btn-block mt-2"><b>Actualizar Info Académica</b></a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        {{-- Tarjeta de Situación Académica --}}
        @forelse ($modulosCursados as $modulo)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <strong>{{ $modulo->nombre }}</strong> - Estado:
                    <span class="badge bg-info">{{ $modulo->pivot->estado }}</span>
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Materia</th>
                            <th>Nota Final/Parcial</th>
                            <th>Estado</th>
                            <th>Docente</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($modulo->materias as $materia)
                        @php
                        $data = $materiasData->get($materia->id);
                        // Buscamos la información del horario para esta materia
                        $horarioItem = $horarios->get($materia->id);
                        @endphp
                        <tr>
                            <td>{{ $materia->nombre }}</td>
                            @if ($data)
                            <td><span class="badge bg-primary">{{ $data->pivot->nota_final ?? '---' }}</span></td>
                            <td>{{ $data->pivot->estado }}</td>
                            <td>{{ $horarioItem->docente->apellido ?? 'Sin asignar' }}, {{ $horarioItem->docente->nombre ?? '' }}</td>
                            @else
                            <td colspan="3" class="text-center text-muted"><em>No inscripto</em></td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">No hay materias asignadas a este módulo.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @empty
        <div class="alert alert-light">
            No hay información académica registrada para este alumno.
        </div>
        @endforelse
    </div>
</div>
@stop