<?php

namespace App\Http\Controllers;

use App\Models\PlanEstudio;
use Illuminate\Http\Request;

class PlanEstudioController extends Controller
{
    public function index()
    {
        $planes = PlanEstudio::with('modulos.materias.prerequisites')->get();
        return view('planes.index', compact('planes'));
    }

    public function create()
    {
        return view('planes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'resolucion' => 'required|string|max:255',
            'ano_implementacion' => 'required|digits:4|integer',
        ]);

        PlanEstudio::create($request->all());

        return redirect()->route('planes-de-estudio.index')
            ->with('success', 'Plan de Estudio creado exitosamente.');
    }

    public function show(PlanEstudio $planEstudio)
    {
        return view('planes.show', compact('planEstudio'));
    }

    public function edit(PlanEstudio $planEstudio)
    {
        return view('planes.edit', compact('planEstudio'));
    }

    public function update(Request $request, PlanEstudio $planEstudio)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'resolucion' => 'required|string|max:255',
            'ano_implementacion' => 'required|digits:4|integer|min:1990',
        ]);

        $planEstudio->update($request->all());

        return redirect()->route('planes-de-estudio.index')->with('success', 'Plan de Estudio actualizado exitosamente.');
    }

    public function gestionarContenido(PlanEstudio $planEstudio)
    {
        $planEstudio->load('modulos.materias');

        return view('planes.contenido', compact('planEstudio'));
    }

    public function destroy(PlanEstudio $planEstudio)
    {
        $planEstudio->delete();
        return redirect()->route('planes-de-estudio.index')->with('success', 'Plan de Estudio eliminado exitosamente.');
    }
}
