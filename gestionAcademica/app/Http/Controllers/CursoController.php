<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Modulo;
use Illuminate\Http\Request;
use App\Models\Docente;
use App\Models\PlanEstudio;
use App\Models\Horario;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class CursoController extends Controller
{
    public function index()
    {
        // anidaciones
        $planesDeEstudio = PlanEstudio::with([
            'modulos.cursos.modulo',
            'modulos.cursos.horario.materia',
            'modulos.cursos.horario.docente'
        ])->get();

        return view('cursos.index', compact('planesDeEstudio'));
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
        $minTime = '';
        $maxTime = '';

        switch ($curso->turno) {
            case 'Mañana':
                $minTime = '08:00';
                $maxTime = '13:00';
                break;
            case 'Tarde':
                $minTime = '13:20';
                $maxTime = '18:00';
                break;
            case 'Noche':
                $minTime = '18:00';
                $maxTime = '22:00';
                break;
        }

        $materiasDisponibles = $curso->modulo->materias;
        $docentesDisponibles = Docente::all();
        $horario = Horario::where('curso_id', $curso->id)
            ->with(['materia', 'docente'])
            ->orderBy('dia_semana')->orderBy('hora_inicio')->get();

        return view('cursos.horario', compact('curso', 'horario', 'materiasDisponibles', 'docentesDisponibles', 'minTime', 'maxTime'));
    }

    public function addHorario(Request $request, Curso $curso)
    {
        // rangos
        $minTime = '';
        $maxTime = '';

        switch ($curso->turno) {
            case 'Mañana':
                $minTime = '08:00';
                $maxTime = '13:00';
                break;
            case 'Tarde':
                $minTime = '13:20';
                $maxTime = '18:00';
                break;
            case 'Noche':
                $minTime = '18:00';
                $maxTime = '22:00';
                break;
        }

        // Validación de reglas de horario
        $request->validate([
            'materia_id' => 'required|exists:materias,id',
            'docente_id' => 'required|exists:docentes,id',
            'dia_semana' => 'required|string',
            'hora_inicio' => "required|date_format:H:i|after_or_equal:$minTime",
            'hora_fin' => "required|date_format:H:i|after:hora_inicio|before_or_equal:$maxTime",
        ], [
            'hora_inicio.after_or_equal' => "La hora de inicio debe estar dentro del rango del turno ($minTime - $maxTime).",
            'hora_fin.before_or_equal' => "La hora de fin debe estar dentro del rango del turno ($minTime - $maxTime).",
        ]);
        // Verificar conflictos de horario para el docente
        $conflicto = Horario::where('docente_id', $request->docente_id)
            ->exists();

        if ($conflicto) {
            return back()->withInput()->with('error', 'Conflicto de horario: El docente ya tiene una clase asignada...');
        }

        Horario::create([
            'curso_id' => $curso->id,
            'materia_id' => $request->materia_id,
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

    public function descargarHorarioPDF(Curso $curso)
    {
        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
        $horas = [];
        if ($curso->turno == 'Mañana') {
            $horas = ['08:00', '08:40', '09:20', '10:00', '10:40', '11:20', '12:00'];
        } elseif ($curso->turno == 'Noche') {
            $horas = ['18:00', '18:40', '19:20', '20:00', '20:40', '21:20'];
        } else {
            $horas = ['13:20', '14:00', '14:40', '15:20', '16:00', '16:40', '17:20'];
        }
        $horarioOrganizado = $curso->horario->groupBy('dia_semana');

        // array con todos los datos de la vista del PDF
        $data = [
            'curso' => $curso,
            'dias' => $dias,
            'horas' => $horas,
            'horarioOrganizado' => $horarioOrganizado
        ];

        // vista del PDF con los datos
        $pdf = PDF::loadView('cursos.horario_pdf', $data);

        // Devolvemos el PDF para que se descargue
        return $pdf->download('horario-' . $curso->nombre . '.pdf');
    }

    public function destroy(Curso $curso)
    {
        $curso->delete();
        return redirect()->route('cursos.index')->with('success', 'Curso eliminado exitosamente.');
    }
}
