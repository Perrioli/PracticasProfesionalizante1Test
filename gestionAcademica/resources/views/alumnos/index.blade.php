@extends('adminlte::page')

@section('title', 'Listado de Alumnos')

@section('content_header')
<h1>Listado de Alumnos</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Alumnos Registrados</h3>
        <div class="card-tools">
            <a href="{{ route('alumnos.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Alumno
            </a>
        </div>
    </div>
    <div class="card-body">
        {{-- Aquí puedes poner un mensaje de éxito --}}
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>DNI</th>
                    <th>Año Ingreso</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($alumnos as $alumno)
                <tr>
                    <td>{{ $alumno->id }}</td>
                    <td>{{ $alumno->apellido }}, {{ $alumno->nombre }}</td>
                    <td>{{ $alumno->dni }}</td>
                    <td>{{ $alumno->ano_ingreso }}</td>
                    <td>
                        {{-- NUEVO BOTÓN PARA VER PERFIL --}}
                        <a href="{{ route('alumnos.perfil', $alumno) }}" class="btn btn-sm btn-success" title="Ver Perfil">
                            <i class="fas fa-id-card"></i>
                        </a>

                        <a href="{{ route('alumnos.documentacion', $alumno) }}" class="btn btn-sm btn-info" title="Documentación">
                            <i class="fas fa-folder"></i>
                        </a>

                        <a href="{{ route('alumnos.edit', $alumno) }}" class="btn btn-sm btn-warning" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form action="{{ route('alumnos.destroy', $alumno) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?')" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No hay alumnos registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix">
        {{-- Paginación --}}
        {{ $alumnos->links() }}
    </div>
</div>
@stop

@section('css')
{{-- Aquí puedes añadir estilos CSS personalizados si es necesario --}}
@stop

@section('js')
{{-- Aquí puedes añadir scripts JS personalizados si es necesario --}}
@stop