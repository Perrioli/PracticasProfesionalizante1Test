<?php

namespace App\Http\Controllers;

use App\Models\Submateria;
use Illuminate\Http\Request;
use App\Models\Materia;

class SubmateriaController extends Controller
{
    public function index()
    {
        $submaterias = Submateria::with('materias')->get();
        return view('submaterias.index', compact('submaterias'));
    }

    public function create()
    {
        $materias = Materia::all();
        return view('submaterias.create', compact('materias'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'carga_horaria' => 'required|integer',
            'materias' => 'required|array',
        ]);

        $submateria = Submateria::create($validated);
        $submateria->materias()->sync($request->materias);

        return redirect()->route('submaterias.index')->with('success', 'Submateria creada exitosamente.');
    }

    public function edit(Submateria $submateria)
    {
        $materias = Materia::all();
        return view('submaterias.edit', compact('submateria', 'materias'));
    }

    public function update(Request $request, Submateria $submateria)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'carga_horaria' => 'required|integer',
            'materias' => 'required|array',
        ]);
        
        $submateria->update($validated);
        $submateria->materias()->sync($request->materias);

        return redirect()->route('submaterias.index')->with('success', 'Submateria actualizada exitosamente.');
    }
}