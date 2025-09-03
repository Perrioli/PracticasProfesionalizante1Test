@extends('adminlte::page')

@section('title', 'Documentación del Alumno')

@section('content_header')
<h1>Documentación de: {{ $alumno->apellido }}, {{ $alumno->nombre }}</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Checklist de Documentación Requerida</h3>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Tipo de documento</th>
                    <th>Fecha presentación</th>
                    <th>Estado</th>
                    <th style="width: 15%">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($documentosRequeridos as $tipoDoc)
                @php
                // Buscamos si el documento requerido existe en la colección del alumno
                $doc = $documentacionExistente->get($tipoDoc);
                @endphp
                <tr>
                    <td>{{ $tipoDoc }}</td>
                    <td>
                        {{-- Si el documento existe, muestra la fecha formateada --}}
                        {{ $doc ? \Carbon\Carbon::parse($doc->fecha_presentacion)->format('d/m/Y') : '---' }}
                    </td>
                    <td>
                        {{-- Si el documento existe y está entregado, muestra éxito. Si no, pendiente. --}}
                        @if ($doc && $doc->estado == 'Entregado')
                        <span class="badge bg-success">Entregado</span>
                        @else
                        <span class="badge bg-warning">Pendiente</span>
                        @endif
                    </td>
                    <td>
                        {{-- Si el documento NO existe, muestra solo el botón de subir --}}
                        @if (!$doc)
                        <a href="{{ route('documentaciones.create', ['alumno' => $alumno, 'tipo' => $tipoDoc]) }}" class="btn btn-sm btn-primary" title="Subir o Registrar">
                            <i class="fas fa-upload"></i> Registrar
                        </a>
                        {{-- Si el documento SÍ existe, muestra los botones de eliminar y ver/cambiar --}}
                        @else
                        <a href="{{ route('documentaciones.ver', $doc->id) }}" class="btn btn-sm btn-secondary" title="Ver Archivo" target="_blank">
                            <i class="fas fa-eye"></i>
                        </a>
                        <form action="{{ route('documentaciones.destroy', $doc->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?')" title="Eliminar Registro">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<a href="{{ route('alumnos.index') }}" class="btn btn-secondary mt-3">
    <i class="fas fa-arrow-left"></i> Volver al Listado
</a>
@stop