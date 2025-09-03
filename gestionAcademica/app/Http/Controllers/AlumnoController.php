<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Modulo;
use Illuminate\Http\Request;
use App\Models\Submateria;

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
        // Cargar relaciones para mostrar información completa
        $alumno->load('documentaciones', 'pases', 'cursos', 'submaterias');
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

        $modulosCursados = $alumno->modulos()
            ->with('materias.submaterias')
            ->orderBy('orden')
            ->get();

        $submateriasData = $alumno->submaterias()
            ->with('docentes')
            ->get()
            ->keyBy('id');

        return view('alumnos.perfil', compact('alumno', 'fotoPerfil', 'modulosCursados', 'submateriasData'));
    }

    public function editAcademico(Alumno $alumno)
    {
        $modulosCursados = $alumno->modulos()->with('materias.submaterias')->orderBy('orden')->get();
        $submateriasData = $alumno->submaterias()->with('docentes')->get()->keyBy('id');


        $modulosInscriptosIds = $alumno->modulos->pluck('id');

        $modulosDisponibles = Modulo::whereNotIn('id', $modulosInscriptosIds)->orderBy('orden')->get();

        return view('alumnos.academico', compact('alumno', 'modulosCursados', 'submateriasData', 'modulosDisponibles'));
    }

    public function enrollAcademico(Request $request, Alumno $alumno)
    {
        $request->validate(['modulo_id' => 'required|exists:modulos,id']);

        $moduloId = $request->modulo_id;
        $anoLectivo = date('Y');

        $alumno->modulos()->attach($moduloId, ['estado' => 'Cursando', 'ano_lectivo' => $anoLectivo]);

        $modulo = Modulo::with('materias.submaterias')->find($moduloId);
        $submateriasIds = [];
        foreach ($modulo->materias as $materia) {
            $submateriasIds = array_merge($submateriasIds, $materia->submaterias->pluck('id')->toArray());
        }

        $registros = [];
        foreach (array_unique($submateriasIds) as $submateriaId) {
            $registros[$submateriaId] = ['estado' => 'Cursando'];
        }
        $alumno->submaterias()->attach($registros);

        return redirect()->route('alumnos.academico.edit', $alumno)->with('success', 'Alumno inscripto en el módulo exitosamente.');
    }

    public function updateSubmateria(Request $request, Alumno $alumno, Submateria $submateria)
    {
        $request->validate([
            'nota_final' => 'nullable|numeric|min:0|max:10',
            'estado' => 'required|string|in:Cursando,Aprobada,Previa,Libre',
        ]);

        $alumno->submaterias()->updateExistingPivot($submateria->id, [
            'nota_final' => $request->nota_final,
            'estado' => $request->estado,
        ]);

        return back()->with('success', 'Información académica actualizada exitosamente.');
    }
}
