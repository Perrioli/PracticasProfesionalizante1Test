<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Modulo;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    public function index()
    {
        $cursos = Curso::with('modulo')->latest()->paginate(10);
        return view('cursos.index', compact('cursos'));
    }

    public function create()
    {
        $modulos = Modulo::all();
        return view('cursos.create', compact('modulos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'turno' => 'required|in:Mañana,Tarde,Noche',
            'ano_lectivo' => 'required|digits:4|integer',
            'modulo_id' => 'required|exists:modulos,id',
        ]);

        Curso::create($request->all());
        return redirect()->route('cursos.index')->with('success', 'Curso creado exitosamente.');
    }

    public function edit(Curso $curso)
    {
        $modulos = Modulo::all();
        return view('cursos.edit', compact('curso', 'modulos'));
    }

    public function update(Request $request, Curso $curso)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'turno' => 'required|in:Mañana,Tarde,Noche',
            'ano_lectivo' => 'required|digits:4|integer',
            'modulo_id' => 'required|exists:modulos,id',
        ]);

        $curso->update($request->all());
        return redirect()->route('cursos.index')->with('success', 'Curso actualizado exitosamente.');
    }

    public function destroy(Curso $curso)
    {
        $curso->delete();
        return redirect()->route('cursos.index')->with('success', 'Curso eliminado exitosamente.');
    }
}