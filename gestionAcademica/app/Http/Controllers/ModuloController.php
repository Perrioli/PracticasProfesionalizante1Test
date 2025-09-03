<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use App\Models\PlanEstudio;
use Illuminate\Http\Request;

class ModuloController extends Controller
{
    public function index()
    {
        $modulos = Modulo::with('planEstudio')->latest()->paginate(10);
        return view('modulos.index', compact('modulos'));
    }

    public function create(Request $request)
    {
        $planes = PlanEstudio::all();
        $planIdSeleccionado = $request->query('plan_id');
        return view('modulos.create', compact('planes', 'planIdSeleccionado'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'orden' => 'required|integer',
            'ano_correspondiente' => 'required|integer',
            'plan_estudio_id' => 'required|exists:plan_estudios,id',
        ]);

        Modulo::create($request->all());
        return redirect()->route('modulos.index')->with('success', 'Módulo creado exitosamente.');
    }

    public function edit(Modulo $modulo)
    {
        $planes = PlanEstudio::all();
        return view('modulos.edit', compact('modulo', 'planes'));
    }

    public function update(Request $request, Modulo $modulo)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'orden' => 'required|integer',
            'ano_correspondiente' => 'required|integer',
            'plan_estudio_id' => 'required|exists:plan_estudios,id',
        ]);

        $modulo->update($request->all());
        return redirect()->route('modulos.index')->with('success', 'Módulo actualizado exitosamente.');
    }

    public function destroy(Modulo $modulo)
    {
        $modulo->delete();
        return redirect()->route('modulos.index')->with('success', 'Módulo eliminado exitosamente.');
    }
}
