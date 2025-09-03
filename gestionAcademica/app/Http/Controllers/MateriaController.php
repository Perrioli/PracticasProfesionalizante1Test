<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Models\Modulo;
use Illuminate\Http\Request;

class MateriaController extends Controller
{
    public function index()
    {
        $materias = Materia::with('modulo')->get();
        return view('materias.index', compact('materias'));
    }

    public function create(Request $request)
    {
        $modulos = Modulo::with('planEstudio')->get();
        $materias = Materia::orderBy('nombre')->get();
        $moduloIdSeleccionado = $request->query('modulo_id');

        $planId = null;
        if ($moduloIdSeleccionado) {
            $modulo = Modulo::find($moduloIdSeleccionado);
            $planId = $modulo ? $modulo->plan_estudio_id : null;
        }

        return view('materias.create', compact('modulos', 'materias', 'moduloIdSeleccionado', 'planId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'nullable|string|max:50',
            'carga_horaria_total' => 'required|integer',
            'regimen' => 'required|string',
            'modulo_id' => 'required|exists:modulos,id',
            'prerequisites' => 'nullable|array'
        ]);

        $materia = Materia::create($validated);

        if ($request->has('prerequisites')) {
            $materia->prerequisites()->sync($request->prerequisites);
        }

        if ($request->has('plan_id')) {
            return redirect()->route('planes.contenido', $request->plan_id)
                ->with('success', 'Materia añadida al plan exitosamente.');
        }

        return redirect()->route('materias.index')->with('success', 'Materia creada exitosamente.');
    }

    public function show(Materia $materia)
    {
        // Not implemented
    }

    public function edit(Materia $materia)
    {
        $modulos = Modulo::with('planEstudio')->get();
        $materias = Materia::where('id', '!=', $materia->id)->orderBy('nombre')->get();
        $planId = $materia->modulo->plan_estudio_id;

        return view('materias.edit', compact('materia', 'modulos', 'materias', 'planId'));
    }

    public function update(Request $request, Materia $materia)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'nullable|string|max:50',
            'carga_horaria_total' => 'required|integer',
            'regimen' => 'required|string',
            'modulo_id' => 'required|exists:modulos,id',
            'prerequisites' => 'nullable|array'
        ]);

        $materia->update($validated);
        $materia->prerequisites()->sync($request->prerequisites ?? []);

        if ($request->has('plan_id')) {
            return redirect()->route('planes.contenido', $request->plan_id)
                ->with('success', 'Materia actualizada exitosamente.');
        }

        return redirect()->route('materias.index')->with('success', 'Materia actualizada exitosamente.');
    }

    public function destroy(Materia $materia)
    {
        $materia->delete();

        return back()->with('success', 'Materia eliminada exitosamente.');
    }
}
