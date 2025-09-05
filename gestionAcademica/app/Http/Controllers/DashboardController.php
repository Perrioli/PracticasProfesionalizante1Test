<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Docente;
use App\Models\Curso;
use App\Models\PlanEstudio;

class DashboardController extends Controller
{
    public function index()
    {
        // Tarjetas de Estadísticas 
        $totalAlumnos = Alumno::count();
        $totalDocentes = Docente::count();
        $totalCursos = Curso::where('ano_lectivo', date('Y'))->count();
        $totalPlanes = PlanEstudio::count();

        // documentos son requeridos en total.
        $totalDocsRequeridos = 5; // (DNI, Partida, Analítico, Foto, Pase)

        $alumnosCompletosIds = Alumno::whereHas('documentaciones', function ($query) {
            $query->where('estado', 'Entregado');
        }, '>=', $totalDocsRequeridos)->pluck('id');

        $alumnosConPendientes = Alumno::whereNotIn('id', $alumnosCompletosIds)
                                    ->latest()
                                    ->paginate(5);

        return view('dashboard', compact(
            'totalAlumnos',
            'totalDocentes',
            'totalCursos',
            'totalPlanes',
            'alumnosConPendientes'
        ));
    }
}