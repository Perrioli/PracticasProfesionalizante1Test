<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Modulo;
use Illuminate\Http\Request;
use App\Models\Docente;
use App\Models\PlanEstudio;
use App\Models\Horario;
use Illuminate\Validation\Rule;

class CursoController extends Controller
{
    public function index()
    {
        $cursos = Curso::with([
            'modulo.planEstudio',
            'horario.materia',
            'horario.docente'
        ])->latest()->get();

        return view('cursos.index', compact('cursos'));
    }

    public function create()
    {
        $planes = PlanEstudio::orderBy('nombre')->get();
        $modulos = Modulo::orderBy('orden')->get();
        return view('cursos.create', compact('planes', 'modulos'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'turno' => 'required|in:Mañana,Tarde,Noche',
            'ano_lectivo' => 'required|digits:4|integer',
            'modulo_id' => [
                'required',
                'exists:modulos,id',
                // La regla de unicidad ahora se aplica aquí:
                Rule::unique('cursos')->where(function ($query) use ($request) {
                    return $query->where('turno', $request->turno)
                        ->where('ano_lectivo', $request->ano_lectivo);
                }),
            ],
        ], [
            // Mensaje de error personalizado para la nueva regla
            'modulo_id.unique' => 'Ya existe un curso para este módulo, turno y año lectivo.'
        ]);

        Curso::create($request->all());

        return redirect()->route('cursos.index')->with('success', 'Curso creado exitosamente.');
    }

    public function edit(Curso $curso)
    {
        $planes = PlanEstudio::orderBy('nombre')->get();
        $modulos = Modulo::orderBy('orden')->get();
        return view('cursos.edit', compact('curso', 'planes', 'modulos'));
    }

    public function update(Request $request, Curso $curso)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'turno' => 'required|in:Mañana,Tarde,Noche',
            'ano_lectivo' => 'required|digits:4|integer',
            'modulo_id' => [
                'required',
                'exists:modulos,id',
                // La misma regla, pero ignorando el ID del curso actual
                Rule::unique('cursos')->where(function ($query) use ($request) {
                    return $query->where('turno', $request->turno)
                        ->where('ano_lectivo', $request->ano_lectivo);
                })->ignore($curso->id),
            ],
        ], [
            'modulo_id.unique' => 'Ya existe un curso para este módulo, turno y año lectivo.'
        ]);

        $curso->update($request->all());

        return redirect()->route('cursos.index')->with('success', 'Curso actualizado exitosamente.');
    }

    public function horario(Curso $curso)
    {
        $materiasDisponibles = $curso->modulo->materias;
        $docentesDisponibles = Docente::all();

        $horario = Horario::where('curso_id', $curso->id)
            ->with(['materia', 'docente'])
            ->orderBy('dia_semana')
            ->orderBy('hora_inicio')
            ->get();

        return view('cursos.horario', compact('curso', 'horario', 'materiasDisponibles', 'docentesDisponibles'));
    }

    public function addHorario(Request $request, Curso $curso)
    {
        $request->validate([
            'materia_id' => 'required|exists:materias,id',
            'docente_id' => 'required|exists:docentes,id',
            'dia_semana' => 'required|string',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
        ]);

        // Verificación de conflicto de horario para el docente
        $conflicto = Horario::where('docente_id', $request->docente_id)
            ->where('dia_semana', $request->dia_semana)
            ->where(function ($query) use ($request) {
                $query->where('hora_inicio', '<', $request->hora_fin)
                    ->where('hora_fin', '>', $request->hora_inicio);
            })
            ->exists();

        if ($conflicto) {
            return back()->withInput()
                ->with('error', 'Conflicto de horario: El docente ya tiene una clase asignada en ese día y rango horario.');
        }

        $curso->materias()->attach($request->materia_id, [
            'docente_id' => $request->docente_id,
            'dia_semana' => $request->dia_semana,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
        ]);

        return back()->with('success', 'Materia añadida al horario exitosamente.');
    }

    public function destroyHorario(Horario $horario)
    {
        $horario->delete();
        return back()->with('success', 'Asignación eliminada del horario exitosamente.');
    }

    public function destroy(Curso $curso)
    {
        $curso->delete();
        return redirect()->route('cursos.index')->with('success', 'Curso eliminado exitosamente.');
    }
}
