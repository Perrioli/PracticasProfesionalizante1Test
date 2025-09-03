<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use Illuminate\Http\Request;

class DocenteController extends Controller
{
    public function index()
    {
        $docentes = Docente::latest()->paginate(15);
        return view('docentes.index', compact('docentes'));
    }

    public function create()
    {
        return view('docentes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'dni' => 'required|string|max:15|unique:docentes,dni',
            'titulo' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:docentes,email',
        ]);

        Docente::create($request->all());
        return redirect()->route('docentes.index')->with('success', 'Docente creado exitosamente.');
    }

    public function edit(Docente $docente)
    {
        return view('docentes.edit', compact('docente'));
    }

    public function update(Request $request, Docente $docente)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'dni' => 'required|string|max:15|unique:docentes,dni,' . $docente->id,
            'titulo' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:docentes,email,' . $docente->id,
        ]);

        $docente->update($request->all());
        return redirect()->route('docentes.index')->with('success', 'Docente actualizado exitosamente.');
    }

    public function destroy(Docente $docente)
    {
        $docente->delete();
        return redirect()->route('docentes.index')->with('success', 'Docente eliminado exitosamente.');
    }
}