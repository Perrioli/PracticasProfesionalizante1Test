@extends('adminlte::page')

@section('title', 'Gestión de Docentes')

@section('content_header')
    <h1>Gestión de Docentes</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de Docentes</h3>
            <div class="card-tools">
                <a href="{{ route('docentes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Docente
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Completo</th>
                        <th>DNI</th>
                        <th>Email</th>
                        <th>Título</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($docentes as $docente)
                        <tr>
                            <td>{{ $docente->id }}</td>
                            <td>{{ $docente->apellido }}, {{ $docente->nombre }}</td>
                            <td>{{ $docente->dni }}</td>
                            <td>{{ $docente->email }}</td>
                            <td>{{ $docente->titulo }}</td>
                            <td>
                                <a href="{{ route('docentes.perfil', $docente) }}" class="btn btn-sm btn-success" title="Ver Perfil">
                                    <i class="fas fa-id-card"></i>
                                </a>
                                <a href="{{ route('docentes.edit', $docente) }}" class="btn btn-sm btn-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">No hay docentes registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop