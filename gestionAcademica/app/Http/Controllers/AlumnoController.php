<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use Illuminate\Http\Request;

class AlumnoController extends Controller
{
    public function index()
    {
        $alumnos = Alumno::latest()->paginate(15);
        return view('alumnos.index', compact('alumnos'));
    }

    public function create()
    {
        return view('alumnos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'dni' => 'required|string|max:15|unique:alumnos,dni',
            'fecha_nacimiento' => 'required|date',
            'escuela_origen' => 'required|string|max:255',
            'ano_ingreso' => 'required|digits:4|integer',
        ]);

        Alumno::create($request->all());
        return redirect()->route('alumnos.index')->with('success', 'Alumno creado exitosamente.');
    }

    public function show(Alumno $alumno)
    {
        // Cargar relaciones para mostrar información completa
        $alumno->load('documentaciones', 'pases', 'cursos', 'submaterias');
        return view('alumnos.show', compact('alumno'));
    }

    public function edit(Alumno $alumno)
    {
        return view('alumnos.edit', compact('alumno'));
    }

    public function update(Request $request, Alumno $alumno)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'dni' => 'required|string|max:15|unique:alumnos,dni,' . $alumno->id,
            'fecha_nacimiento' => 'required|date',
            'escuela_origen' => 'required|string|max:255',
            'ano_ingreso' => 'required|digits:4|integer',
        ]);

        $alumno->update($request->all());
        return redirect()->route('alumnos.index')->with('success', 'Alumno actualizado exitosamente.');
    }

    public function destroy(Alumno $alumno)
    {
        $alumno->delete();
        return redirect()->route('alumnos.index')->with('success', 'Alumno eliminado exitosamente.');
    }
}