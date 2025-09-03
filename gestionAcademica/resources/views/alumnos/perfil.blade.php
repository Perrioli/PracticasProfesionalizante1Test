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
                </ul>

                <a href="{{ route('alumnos.documentacion', $alumno) }}" class="btn btn-primary btn-block"><b>Ver Documentación</b></a>
                <a href="{{ route('alumnos.academico.edit', $alumno) }}" class="btn btn-info btn-block mt-2"><b>Actualizar Info Académica</b></a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        {{-- Itera sobre cada módulo que el alumno ha cursado --}}
        @forelse ($modulosCursados as $modulo)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <strong>Módulo {{ $modulo->orden }} ({{ $modulo->ano_correspondiente }}er Año)</strong> - Estado:
                    @if ($modulo->pivot->estado == 'Aprobado')
                    <span class="badge bg-success">{{ $modulo->pivot->estado }}</span>
                    @else
                    <span class="badge bg-info">{{ $modulo->pivot->estado }}</span>
                    @endif
                </h3>
                {{-- Botón para colapsar/expandir la tabla del módulo --}}
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Materia</th>
                            <th>Submateria</th>
                            <th>Nota Final/Parcial</th>
                            <th>Estado</th>
                            <th>Docente</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Itera a través de la estructura anidada: Modulo -> Materias -> Submaterias --}}
                        @foreach ($modulo->materias as $materia)
                        @foreach ($materia->submaterias as $submateria)
                        @php
                        // Busca la información específica de esta submateria para este alumno
                        $data = $submateriasData->get($submateria->id);
                        @endphp
                        <tr>
                            <td>{{ $materia->nombre }}</td>
                            <td>{{ $submateria->nombre }}</td>

                            {{-- Si se encontró información, la mostramos --}}
                            @if ($data)
                            <td>
                                <span class="badge bg-primary">{{ $data->pivot->nota_final ?? '---' }}</span>
                            </td>
                            <td>{{ $data->pivot->estado }}</td>
                            <td>
                                {{-- Muestra el primer docente asignado a esa submateria --}}
                                {{ $data->docentes->first()->apellido ?? 'Sin asignar' }}
                            </td>
                            @else
                            {{-- Si el alumno no está inscripto en esta submateria, se indica --}}
                            <td colspan="3" class="text-center text-muted"><em>No inscripto</em></td>
                            @endif
                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @empty
        <div class="card">
            <div class="card-body">
                No hay información académica registrada para este alumno.
            </div>
        </div>
        @endforelse
    </div>
</div>
@stop