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
Route::resource('docentes', DocenteController::class);
Route::resource('cursos', CursoController::class);
Route::resource('planes-de-estudio', PlanEstudioController::class)->parameters(['planes-de-estudio' => 'planEstudio']);
Route::resource('modulos', ModuloController::class);
Route::resource('materias', MateriaController::class);
Route::resource('submaterias', SubmateriaController::class);

require __DIR__.'/auth.php';
