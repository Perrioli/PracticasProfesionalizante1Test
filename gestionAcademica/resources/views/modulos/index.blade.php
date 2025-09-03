@extends('adminlte::page')

@section('title', 'Gestión de Módulos')

@section('content_header')
    <h1>Gestión de Módulos</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de Módulos</h3>
            <div class="card-tools">
                <a href="{{ route('modulos.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Módulo
                </a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre del Módulo</th>
                        <th>Plan de Estudio</th>
                        <th>Orden</th>
                        <th>Año</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($modulos as $modulo)
                        <tr>
                            <td>{{ $modulo->id }}</td>
                            <td>{{ $modulo->nombre }}</td>
                            <td>{{ $modulo->planEstudio->nombre ?? 'No asignado' }}</td>
                            <td>{{ $modulo->orden }}</td>
                            <td>{{ $modulo->ano_correspondiente }}</td>
                            <td>
                                <a href="{{ route('modulos.edit', $modulo) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                {{-- Aquí puedes añadir un formulario de eliminación si lo necesitas --}}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">No hay módulos registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop