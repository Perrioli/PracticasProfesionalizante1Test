<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Modulo;
use App\Models\Materia;
use App\Models\PlanEstudio;
use Illuminate\Http\Request;

class AlumnoController extends Controller
{
    public function index()
    {
        $alumnos = Alumno::latest()->paginate(15);
        return view('alumnos.index', compact('alumnos'));
    }

    public function create()
    {
        return view('alumnos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'dni' => 'required|string|max:15|unique:alumnos,dni',
            'fecha_nacimiento' => 'required|date',
            'escuela_origen' => 'required|string|max:255',
            'ano_ingreso' => 'required|digits:4|integer',
        ]);

        Alumno::create($request->all());
        return redirect()->route('alumnos.index')->with('success', 'Alumno creado exitosamente.');
    }

    public function show(Alumno $alumno)
    {
        $alumno->load('documentaciones', 'pases', 'modulos', 'materias');
        return view('alumnos.show', compact('alumno'));
    }

    public function edit(Alumno $alumno)
    {
        return view('alumnos.edit', compact('alumno'));
    }

    public function update(Request $request, Alumno $alumno)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'dni' => 'required|string|max:15|unique:alumnos,dni,' . $alumno->id,
            'fecha_nacimiento' => 'required|date',
            'escuela_origen' => 'required|string|max:255',
            'ano_ingreso' => 'required|digits:4|integer',
        ]);

        $alumno->update($request->all());
        return redirect()->route('alumnos.index')->with('success', 'Alumno actualizado exitosamente.');
    }

    public function destroy(Alumno $alumno)
    {
        $alumno->delete();
        return redirect()->route('alumnos.index')->with('success', 'Alumno eliminado exitosamente.');
    }

    public function documentacion(Alumno $alumno)
    {
        $documentosRequeridos = [
            'DNI',
            'Partida de nacimiento',
            'Certificado analítico',
            'Foto 4x4',
            'Pase',
        ];

        $documentacionExistente = $alumno->documentaciones->keyBy('tipo_documento');

        return view('alumnos.documentacion', compact('alumno', 'documentosRequeridos', 'documentacionExistente'));
    }

    public function perfil(Alumno $alumno)
    {
        $fotoPerfil = $alumno->documentaciones()->where('tipo_documento', 'Foto 4x4')->first();
        $modulosCursados = $alumno->modulos()->with('planEstudio', 'materias')->orderBy('orden')->get();
        $carrera = $modulosCursados->isNotEmpty() ? $modulosCursados->first()->planEstudio : null;
        $materiasData = $alumno->materias()->with('docentes')->get()->keyBy('id');

        return view('alumnos.perfil', compact('alumno', 'fotoPerfil', 'modulosCursados', 'materiasData', 'carrera'));
    }

    public function editAcademico(Request $request, Alumno $alumno)
    {
        $modulosCursados = $alumno->modulos()->with('materias')->orderBy('orden')->get();
        $materiasData = $alumno->materias()->with('docentes')->get()->keyBy('id');
        $planesDeEstudio = PlanEstudio::orderBy('nombre')->get();
        $modulosInscriptosIds = $alumno->modulos->pluck('id');
        $todosLosModulos = Modulo::with('prerequisite')->whereNotIn('id', $modulosInscriptosIds)->orderBy('orden')->get();
        $modulosDisponibles = $todosLosModulos->filter(function ($modulo) use ($alumno) {
            if (!$modulo->prerequisite) return true;
            return $alumno->haAprobadoModulo($modulo->prerequisite);
        });

        $materiasParaInscribir = null;
        if ($request->has('ver_modulo_id')) {
            $moduloSeleccionado = Modulo::with('materias')->find($request->ver_modulo_id);
            if ($moduloSeleccionado) {
                $materiasParaInscribir = $moduloSeleccionado->materias;
            }
        }

        return view('alumnos.academico', compact(
            'alumno',
            'modulosCursados',
            'materiasData',
            'planesDeEstudio',
            'modulosDisponibles',
            'materiasParaInscribir'
        ));
    }

    public function enrollAcademico(Request $request, Alumno $alumno)
    {
        $request->validate(['modulo_id' => 'required|exists:modulos,id']);
        $moduloId = $request->modulo_id;

        $alumno->modulos()->attach($moduloId, ['estado' => 'Cursando', 'ano_lectivo' => date('Y')]);

        $modulo = Modulo::with('materias')->find($moduloId);
        $registros = [];
        foreach ($modulo->materias as $materia) {
            $registros[$materia->id] = ['estado' => 'Cursando'];
        }
        $alumno->materias()->attach($registros);

        return redirect()->route('alumnos.academico.edit', $alumno)->with('success', 'Alumno inscripto en el módulo exitosamente.');
    }
    public function updateMateria(Request $request, Alumno $alumno, Materia $materia)
    {
        $request->validate([
            'nota_final' => 'nullable|numeric|min:0|max:10',
            'estado' => 'required|string|in:Cursando,Aprobada,Previa,Libre',
        ]);

        if (in_array($request->estado, ['Cursando', 'Aprobada'])) {

            if (!$alumno->haAprobadoCorrelativas($materia)) {

                return back()->with('error', 'El alumno no ha aprobado todas las correlativas necesarias para esta materia.');
            }
        }

        $alumno->materias()->updateExistingPivot($materia->id, [
            'nota_final' => $request->nota_final,
            'estado' => $request->estado,
        ]);

        return back()->with('success', 'Información académica actualizada exitosamente.');
    }

    public function destroyMateria(Alumno $alumno, Materia $materia)
    {
        $alumno->materias()->detach($materia->id);

        return back()->with('success', 'Materia quitada del alumno exitosamente.');
    }
    public function enrollMaterias(Request $request)
    {
        $request->validate([
            'alumno_id' => 'required|exists:alumnos,id',
            'materias' => 'required|array'
        ]);

        $alumno = Alumno::find($request->alumno_id);
        $anoLectivo = date('Y');

        if ($request->has('modulo_id')) {
            $alumno->modulos()->syncWithoutDetaching([
                $request->modulo_id => ['estado' => 'Cursando', 'ano_lectivo' => $anoLectivo]
            ]);
        }

        $registros = [];
        foreach ($request->materias as $materiaId) {
            $registros[$materiaId] = ['estado' => 'Cursando'];
        }

        $alumno->materias()->syncWithoutDetaching($registros);

        return redirect()->route('alumnos.academico.edit', $alumno)
            ->with('success', 'Alumno inscripto en las materias seleccionadas.');
    }
}
