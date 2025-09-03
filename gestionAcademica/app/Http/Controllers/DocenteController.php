<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        return redirect()->route('docentes.index')
            ->with('success', 'Docente creado exitosamente.');
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

    public function perfil(Docente $docente)
    {
        $asignaciones = DB::table('curso_materia_docente')
            ->join('cursos', 'curso_materia_docente.curso_id', '=', 'cursos.id')
            ->join('materias', 'curso_materia_docente.materia_id', '=', 'materias.id')
            ->where('curso_materia_docente.docente_id', $docente->id)
            ->select(
                'cursos.nombre as curso_nombre',
                'materias.nombre as materia_nombre',
                'dia_semana',
                'hora_inicio',
                'hora_fin'
            )
            ->orderBy('curso_nombre')->orderBy('dia_semana')->orderBy('hora_inicio')
            ->get();

        return view('docentes.perfil', compact('docente', 'asignaciones'));
    }

    public function destroy(Docente $docente)
    {
        $docente->delete();
        return redirect()->route('docentes.index')->with('success', 'Docente eliminado exitosamente.');
    }
}
