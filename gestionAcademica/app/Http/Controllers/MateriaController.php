<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Models\Modulo;
use Illuminate\Http\Request;

class MateriaController extends Controller
{
    public function index()
    {
        $materias = Materia::with('modulo')->latest()->paginate(10);
        return view('materias.index', compact('materias'));
    }

    public function create()
    {
        $modulos = Modulo::all();
        return view('materias.create', compact('modulos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'carga_horaria_total' => 'required|integer',
            'modulo_id' => 'required|exists:modulos,id',
        ]);

        Materia::create($request->all());
        return redirect()->route('materias.index')->with('success', 'Materia creada exitosamente.');
    }

    public function edit(Materia $materia)
    {
        $modulos = Modulo::all();
        return view('materias.edit', compact('materia', 'modulos'));
    }

    public function update(Request $request, Materia $materia)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'carga_horaria_total' => 'required|integer',
            'modulo_id' => 'required|exists:modulos,id',
        ]);

        $materia->update($request->all());
        return redirect()->route('materias.index')->with('success', 'Materia actualizada exitosamente.');
    }

    public function destroy(Materia $materia)
    {
        $materia->delete();
        return redirect()->route('materias.index')->with('success', 'Materia eliminada exitosamente.');
    }
}