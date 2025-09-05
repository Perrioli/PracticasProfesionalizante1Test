<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\ModuloController;
use App\Http\Controllers\PlanEstudioController;
use App\Http\Controllers\DocumentacionController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas para Alumnos
Route::resource('alumnos', AlumnoController::class);
Route::get('alumnos/{alumno}/documentacion', [AlumnoController::class, 'documentacion'])->name('alumnos.documentacion');
Route::get('alumnos/{alumno}/perfil', [AlumnoController::class, 'perfil'])->name('alumnos.perfil');
Route::get('alumnos/{alumno}/academico/edit', [AlumnoController::class, 'editAcademico'])->name('alumnos.academico.edit');
Route::post('alumnos/{alumno}/academico/enroll', [AlumnoController::class, 'enrollAcademico'])->name('alumnos.academico.enroll');
Route::put('alumnos/{alumno}/materias/{materia}', [AlumnoController::class, 'updateMateria'])->name('alumnos.materia.update');
Route::delete('alumnos/{alumno}/materias/{materia}', [AlumnoController::class, 'destroyMateria'])->name('alumnos.materia.destroy');
Route::post('/alumnos/materias/enroll', [AlumnoController::class, 'enrollMaterias'])->name('alumnos.materias.enroll');
Route::post('alumnos/{alumno}/materias/{materia}/enroll', [AlumnoController::class, 'enrollSingleMateria'])->name('alumnos.materia.enroll');


// Rutas para la estructura académica
Route::resource('planes-de-estudio', PlanEstudioController::class)->parameters(['planes-de-estudio' => 'planEstudio']);
Route::get('/planes-de-estudio/{planEstudio}/contenido', [PlanEstudioController::class, 'gestionarContenido'])->name('planes.contenido');
Route::resource('modulos', ModuloController::class);
Route::resource('materias', MateriaController::class);

// Rutas para Docentes y Cursos
Route::resource('docentes', DocenteController::class);
Route::get('docentes/{docente}/perfil', [DocenteController::class, 'perfil'])->name('docentes.perfil');

Route::resource('cursos', CursoController::class);
Route::get('/cursos/{curso}/horario', [CursoController::class, 'horario'])->name('cursos.horario');
Route::post('/cursos/{curso}/horario', [CursoController::class, 'addHorario'])->name('cursos.horario.store');
Route::delete('/horarios/{horario}', [CursoController::class, 'destroyHorario'])->name('horarios.destroy');

// Rutas para Documentación
Route::get('/alumnos/{alumno}/documentaciones/create', [DocumentacionController::class, 'create'])->name('documentaciones.create');
Route::post('/alumnos/{alumno}/documentaciones', [DocumentacionController::class, 'store'])->name('documentaciones.store');
Route::delete('/documentaciones/{documentacion}', [DocumentacionController::class, 'destroy'])->name('documentaciones.destroy');
Route::get('/documentaciones/{documentacion}/ver', [DocumentacionController::class, 'verArchivo'])->name('documentaciones.ver');

// Rutas para Documentación PDF
Route::get('/cursos/{curso}/horario/pdf', [CursoController::class, 'descargarHorarioPDF'])->name('cursos.horario.pdf');


require __DIR__ . '/auth.php';