<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\ModuloController;
use App\Http\Controllers\PlanEstudioController;
use App\Http\Controllers\SubmateriaController;
use App\Http\Controllers\DocumentacionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('alumnos', AlumnoController::class);
Route::get('alumnos/{alumno}/documentacion', [AlumnoController::class, 'documentacion'])->name('alumnos.documentacion');
Route::get('alumnos/{alumno}/perfil', [AlumnoController::class, 'perfil'])->name('alumnos.perfil');
Route::get('alumnos/{alumno}/academico/edit', [AlumnoController::class, 'editAcademico'])->name('alumnos.academico.edit');
Route::post('alumnos/{alumno}/academico/enroll', [AlumnoController::class, 'enrollAcademico'])->name('alumnos.academico.enroll');
Route::put('alumnos/{alumno}/submaterias/{submateria}', [AlumnoController::class, 'updateSubmateria'])->name('alumnos.submateria.update');

Route::resource('planes-de-estudio', PlanEstudioController::class);
Route::get('/planes-de-estudio/{planEstudio}/contenido', [PlanEstudioController::class, 'gestionarContenido'])->name('planes.contenido');

Route::resource('docentes', DocenteController::class);
Route::resource('cursos', CursoController::class);
Route::resource('planes-de-estudio', PlanEstudioController::class)->parameters(['planes-de-estudio' => 'planEstudio']);
Route::resource('modulos', ModuloController::class);
Route::resource('materias', MateriaController::class);
Route::resource('submaterias', SubmateriaController::class);

Route::get('/alumnos/{alumno}/documentaciones/create', [DocumentacionController::class, 'create'])->name('documentaciones.create');
Route::post('/alumnos/{alumno}/documentaciones', [DocumentacionController::class, 'store'])->name('documentaciones.store');
Route::delete('/documentaciones/{documentacion}', [DocumentacionController::class, 'destroy'])->name('documentaciones.destroy');
Route::get('/documentaciones/{documentacion}/ver', [DocumentacionController::class, 'verArchivo'])->name('documentaciones.ver');

require __DIR__ . '/auth.php';
