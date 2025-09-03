@extends('adminlte::page')
@section('title', 'Submaterias')
@section('content_header') <h1>Gestión de Submaterias</h1> @stop
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Listado de Submaterias</h3>
        <div class="card-tools">
            <a href="{{ route('submaterias.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nueva Submateria</a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Materias a las que pertenece</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($submaterias as $submateria)
                <tr>
                    <td>{{ $submateria->id }}</td>
                    <td>{{ $submateria->nombre }}</td>
                    <td>
                        @foreach($submateria->materias as $materia)
                            <span class="badge bg-secondary">{{ $materia->nombre }}</span>
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route('submaterias.edit', $submateria) }}" class="btn btn-sm btn-warning">Editar</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop