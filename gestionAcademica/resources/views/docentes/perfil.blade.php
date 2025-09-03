@extends('adminlte::page')

@section('title', 'Perfil del Docente')

@section('content_header')
    <h1>Perfil del Docente</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-4">
            {{-- Tarjeta de Perfil e Información Personal --}}
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        {{-- Imagen placeholder --}}
                        <img class="profile-user-img img-fluid img-circle"
                             src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png"
                             alt="Foto de perfil del docente">
                    </div>

                    <h3 class="profile-username text-center">{{ $docente->nombre }} {{ $docente->apellido }}</h3>
                    <p class="text-muted text-center">{{ $docente->titulo }}</p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>DNI</b> <a class="float-right">{{ $docente->dni }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Email</b> <a class="float-right">{{ $docente->email }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            {{-- Tarjeta de Asignaciones Académicas --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Materias y Cursos Asignados</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Curso</th>
                                <th>Materia</th>
                                <th>Horario</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($asignaciones as $asignacion)
                                <tr>
                                    <td>{{ $asignacion->curso_nombre }}</td>
                                    <td>{{ $asignacion->materia_nombre }}</td>
                                    <td>
                                        {{ $asignacion->dia_semana }} de 
                                        {{ \Carbon\Carbon::parse($asignacion->hora_inicio)->format('H:i') }} a 
                                        {{ \Carbon\Carbon::parse($asignacion->hora_fin)->format('H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Este docente no tiene materias asignadas actualmente.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop