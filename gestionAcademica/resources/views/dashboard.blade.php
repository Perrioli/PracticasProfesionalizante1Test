@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Dashboard</h1>
@stop

@section('content')
{{-- Fila con las Tarjetas de Estadísticas --}}
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalAlumnos }}</h3>
                <p>Alumnos Registrados</p>
            </div>
            <div class="icon"><i class="fas fa-user-graduate"></i></div>
            <a href="{{ route('alumnos.index') }}" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $totalDocentes }}</h3>
                <p>Docentes Registrados</p>
            </div>
            <div class="icon"><i class="fas fa-chalkboard-teacher"></i></div>
            <a href="{{ route('docentes.index') }}" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $totalCursos }}</h3>
                <p>Cursos en {{ date('Y') }}</p>
            </div>
            <div class="icon"><i class="fas fa-school"></i></div>
            <a href="{{ route('cursos.index') }}" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $totalPlanes }}</h3>
                <p>Planes de Estudio</p>
            </div>
            <div class="icon"><i class="fas fa-book-open"></i></div>
            <a href="{{ route('planes-de-estudio.index') }}" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>
{{-- Fila con los Gráficos --}}
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Distribución de Alumnos por Carrera</h3>
            </div>
            <div class="card-body">
                {{-- El canvas donde se dibujará el gráfico --}}
                <canvas id="alumnosPorCarreraChart"></canvas>
            </div>
        </div>
    </div>
    {{-- Columna para el Gráfico de Barras --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Cursos por Turno ({{ date('Y') }})</h3>
            </div>
            <div class="card-body"><canvas id="cursosPorTurnoChart"></canvas></div>
        </div>
    </div>
</div>

{{-- Fila con la Lista Paginada --}}
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Alumnos con Documentación Pendiente</h3>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @forelse ($alumnosConPendientes as $alumno)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $alumno->apellido }}, {{ $alumno->nombre }}
                        <a href="{{ route('alumnos.perfil', $alumno) }}" class="btn btn-sm btn-info">Ver Perfil</a>
                    </li>
                    @empty
                    <li class="list-group-item">No hay alumnos con documentación pendiente.</li>
                    @endforelse
                </ul>
            </div>
            <div class="card-footer">
                {{-- ¡Aquí está la magia de la paginación! --}}
                {{ $alumnosConPendientes->links() }}
            </div>
        </div>
    </div>
</div>



@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // --- GRÁFICO DE ALUMNOS POR CARRERA ---
    const carreraData = @json($alumnosPorCarreraData);
    const turnoData = @json($cursosPorTurnoData);
    const carreraLabels = carreraData.map(item => item.carrera);
    const carreraTotals = carreraData.map(item => item.total);


    new Chart(document.getElementById('alumnosPorCarreraChart'), {
        type: 'doughnut',
        data: {
            labels: carreraLabels,
            datasets: [{
                label: 'Total de Alumnos',
                data: carreraTotals,
                backgroundColor: [ // Puedes añadir más colores
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                ],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    });
    new Chart(document.getElementById('cursosPorTurnoChart'), {
        type: 'doughnut',
        data: {
            labels: turnoData.map(item => item.turno),
            datasets: [{
                label: 'Total de Cursos',
                data: turnoData.map(item => item.total),
                backgroundColor: 'rgba(23, 162, 184, 0.7)',
                borderColor: 'rgba(23, 162, 184, 1)',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    });
</script>
@stop