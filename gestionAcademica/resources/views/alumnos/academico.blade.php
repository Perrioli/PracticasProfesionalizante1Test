@extends('adminlte::page')

@section('title', 'Actualizar Info Académica')

@section('content_header')
<h1>Actualizar Información Académica</h1>
<h4>Alumno: {{ $alumno->apellido }}, {{ $alumno->nombre }}</h4>
@stop

@section('content')

{{-- Muestra el mensaje de éxito si existe --}}
@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- Tarjeta para Inscribir a un Nuevo Módulo --}}
<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">Inscribir a Nuevo Módulo</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('alumnos.academico.enroll', $alumno) }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="modulo_id">Seleccionar Módulo</label>
                        <select name="modulo_id" class="form-control">
                            <option value="">-- Elija un módulo --</option>
                            @foreach ($modulosDisponibles as $modulo)
                            <option value="{{ $modulo->id }}">
                                Módulo {{ $modulo->orden }} ({{ $modulo->ano_correspondiente }}er Año) - {{ $modulo->nombre }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4 align-self-end">
                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-block">Inscribir</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Mostramos los módulos que el alumno ya está cursando (igual que en el perfil) --}}
@if($modulosCursados->isNotEmpty())
<hr>
<h4 class="mb-3">Módulos Inscriptos</h4>
@foreach ($modulosCursados as $modulo)
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <strong>Módulo {{ $modulo->orden }} ({{ $modulo->ano_correspondiente }}er Año)</strong> - Estado:
            <span class="badge bg-info">{{ $modulo->pivot->estado }}</span>
        </h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Materia</th>
                    <th>Submateria</th>
                    <th style="width: 120px;">Nota Final</th>
                    <th style="width: 180px;">Estado</th>
                    <th>Docente</th>
                    <th style="width: 100px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($modulo->materias as $materia)
                @foreach ($materia->submaterias as $submateria)
                @php $data = $submateriasData->get($submateria->id); @endphp
                @if ($data)
                {{-- Cada fila es un formulario --}}
                <form action="{{ route('alumnos.submateria.update', ['alumno' => $alumno, 'submateria' => $submateria]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <tr>
                        <td>{{ $materia->nombre }}</td>
                        <td>{{ $submateria->nombre }}</td>
                        <td>
                            <input type="number" name="nota_final" class="form-control form-control-sm"
                                value="{{ $data->pivot->nota_final }}" step="0.01" min="0" max="10">
                        </td>
                        <td>
                            <select name="estado" class="form-control form-control-sm">
                                <option value="Cursando" {{ $data->pivot->estado == 'Cursando' ? 'selected' : '' }}>Cursando</option>
                                <option value="Aprobada" {{ $data->pivot->estado == 'Aprobada' ? 'selected' : '' }}>Aprobada</option>
                                <option value="Previa" {{ $data->pivot->estado == 'Previa' ? 'selected' : '' }}>Previa</option>
                                <option value="Libre" {{ $data->pivot->estado == 'Libre' ? 'selected' : '' }}>Libre</option>
                            </select>
                        </td>
                        <td>{{ $data->docentes->first()->apellido ?? 'Sin asignar' }}</td>
                        <td>
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="fas fa-save"></i> Guardar
                            </button>
                        </td>
                    </tr>
                </form>
                @endif
                @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endforeach
@endif
@stop