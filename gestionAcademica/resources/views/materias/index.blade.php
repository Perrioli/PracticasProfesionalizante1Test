@extends('adminlte::page')

@section('title', 'Gestión de Materias')

@section('content_header')
    <h1>Gestión de Materias</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de Materias</h3>
            <div class="card-tools">
                <a href="{{ route('materias.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nueva Materia
                </a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Módulo al que pertenece</th>
                        <th>Régimen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($materias as $materia)
                        <tr>
                            <td>{{ $materia->id }}</td>
                            <td>{{ $materia->codigo }}</td>
                            <td>{{ $materia->nombre }}</td>
                            <td>{{ $materia->modulo->nombre ?? 'No asignado' }}</td>
                            <td>{{ $materia->regimen }}</td>
                            <td>
                                <a href="{{ route('materias.edit', $materia) }}" class="btn btn-sm btn-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                {{-- Si deseas un botón de eliminar, puedes añadir el formulario aquí --}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No hay materias registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop