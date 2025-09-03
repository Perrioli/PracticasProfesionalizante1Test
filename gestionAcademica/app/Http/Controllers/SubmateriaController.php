<?php

namespace App\Http\Controllers;

use App\Models\Submateria;
use Illuminate\Http\Request;

class SubmateriaController extends Controller
{
    public function index()
    {
        $submaterias = Submateria::latest()->paginate(10);
        return view('submaterias.index', compact('submaterias'));
    }

    public function create()
    {
        return view('submaterias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'carga_horaria' => 'required|integer',
        ]);

        Submateria::create($request->all());
        return redirect()->route('submaterias.index')->with('success', 'Submateria creada exitosamente.');
    }

    public function edit(Submateria $submateria)
    {
        return view('submaterias.edit', compact('submateria'));
    }

    public function update(Request $request, Submateria $submateria)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'carga_horaria' => 'required|integer',
        ]);

        $submateria->update($request->all());
        return redirect()->route('submaterias.index')->with('success', 'Submateria actualizada exitosamente.');
    }

    public function destroy(Submateria $submateria)
    {
        $submateria->delete();
        return redirect()->route('submaterias.index')->with('success', 'Submateria eliminada exitosamente.');
    }
}