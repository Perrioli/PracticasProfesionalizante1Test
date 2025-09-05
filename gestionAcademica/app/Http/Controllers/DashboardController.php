<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB; // <-- Asegúrate de importar la clase DB
use App\Models\Alumno;
use App\Models\Docente;
use App\Models\Curso;
use App\Models\PlanEstudio;
use App\Models\Documentacion;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // --- Datos para las Tarjetas de Estadísticas ---
        $totalAlumnos = Alumno::count();
        $totalDocentes = Docente::count();
        $totalCursos = Curso::where('ano_lectivo', date('Y'))->count();
        $totalPlanes = PlanEstudio::count();

        // --- Datos para la Lista Paginada ---
        $totalDocsRequeridos = 5;
        $alumnosCompletosIds = Alumno::whereHas('documentaciones', function ($query) {
            $query->where('estado', 'Entregado');
        }, '>=', $totalDocsRequeridos)->pluck('id');
        $alumnosConPendientes = Alumno::whereNotIn('id', $alumnosCompletosIds)->latest()->paginate(5);
        
        // --- LÓGICA PARA LOS GRÁFICOS ---
        // Gráfico 1: Alumnos por Carrera
        $alumnosPorCarreraData = DB::table('alumnos')
            ->join('alumno_modulo', 'alumnos.id', '=', 'alumno_modulo.alumno_id')
            ->join('modulos', 'alumno_modulo.modulo_id', '=', 'modulos.id')
            ->join('plan_estudios', 'modulos.plan_estudio_id', '=', 'plan_estudios.id')
            ->select('plan_estudios.nombre as carrera', DB::raw('count(alumnos.id) as total'))
            ->groupBy('plan_estudios.nombre')->get();
            
        // Gráfico 2: Cursos por Turno
        $cursosPorTurnoData = DB::table('cursos')
            ->select('turno', DB::raw('count(*) as total'))
            ->where('ano_lectivo', date('Y'))
            ->groupBy('turno')->get();

        return view('dashboard', compact(
            'totalAlumnos', 'totalDocentes', 'totalCursos', 'totalPlanes',
            'alumnosConPendientes', 'alumnosPorCarreraData', 'cursosPorTurnoData'
        ));
    }
}