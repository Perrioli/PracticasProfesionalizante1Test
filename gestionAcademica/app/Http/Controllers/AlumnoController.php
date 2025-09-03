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
        // 1. Define la lista de todos los documentos que son requeridos.
        $documentosRequeridos = [
            'DNI',
            'Partida de nacimiento',
            'Certificado analítico',
            'Foto 4x4',
            'Pase',
        ];

        // 2. Obtén la documentación que el alumno YA ha presentado.
        // Usamos keyBy() para poder buscar fácilmente por 'tipo_documento'.
        $documentacionExistente = $alumno->documentaciones->keyBy('tipo_documento');

        // 3. Envía ambas listas a la vista.
        return view('alumnos.documentacion', compact('alumno', 'documentosRequeridos', 'documentacionExistente'));
    }

    // En app/Http/Controllers/AlumnoController.php

    public function perfil(Alumno $alumno)
    {
        // busca la foto de perfil (arreglar centrado y vista despues).
        $fotoPerfil = $alumno->documentaciones()->where('tipo_documento', 'Foto 4x4')->first();

        // Con 'with()', cargamos también las materias y submaterias anidadas en una sola consulta.
        $modulosCursados = $alumno->modulos()
            ->with('materias.submaterias')
            ->orderBy('orden')
            ->get();

        //keyBy('id') para poder buscar información en la vista.
        $submateriasData = $alumno->submaterias()
            ->with('docentes')
            ->get()
            ->keyBy('id');

        return view('alumnos.perfil', compact('alumno', 'fotoPerfil', 'modulosCursados', 'submateriasData'));
    }

    public function editAcademico(Alumno $alumno)
    {
        // Obtenemos los módulos que el alumno ya está cursando (igual que en el perfil)
        $modulosCursados = $alumno->modulos()->with('materias.submaterias')->orderBy('orden')->get();
        $submateriasData = $alumno->submaterias()->with('docentes')->get()->keyBy('id');

        // Obtenemos los IDs de los módulos en los que el alumno ya está inscripto
        $modulosInscriptosIds = $alumno->modulos->pluck('id');

        // Buscamos todos los módulos que NO estén en la lista de inscriptos para mostrarlos en el dropdown
        $modulosDisponibles = Modulo::whereNotIn('id', $modulosInscriptosIds)->orderBy('orden')->get();

        return view('alumnos.academico', compact('alumno', 'modulosCursados', 'submateriasData', 'modulosDisponibles'));
    }

    /**
     * Inscribe a un alumno en un nuevo módulo y sus submaterias.
     */
    public function enrollAcademico(Request $request, Alumno $alumno)
    {
        $request->validate(['modulo_id' => 'required|exists:modulos,id']);

        $moduloId = $request->modulo_id;
        $anoLectivo = date('Y'); // Año actual

        // 1. Inscribimos al alumno en el módulo principal
        $alumno->modulos()->attach($moduloId, ['estado' => 'Cursando', 'ano_lectivo' => $anoLectivo]);

        // 2. Obtenemos todas las submaterias de ese módulo
        $modulo = Modulo::with('materias.submaterias')->find($moduloId);
        $submateriasIds = [];
        foreach ($modulo->materias as $materia) {
            $submateriasIds = array_merge($submateriasIds, $materia->submaterias->pluck('id')->toArray());
        }

        // 3. Creamos los registros iniciales para cada submateria en la tabla pivote
        $registros = [];
        foreach (array_unique($submateriasIds) as $submateriaId) {
            $registros[$submateriaId] = ['estado' => 'Cursando'];
        }
        $alumno->submaterias()->attach($registros);

        return redirect()->route('alumnos.academico.edit', $alumno)->with('success', 'Alumno inscripto en el módulo exitosamente.');
    }

    // En app/Http/Controllers/AlumnoController.php

    // ...

    /**
     * Actualiza la información de una submateria para un alumno específico (nota y estado).
     */
    public function updateSubmateria(Request $request, Alumno $alumno, Submateria $submateria)
    {
        $request->validate([
            'nota_final' => 'nullable|numeric|min:0|max:10',
            'estado' => 'required|string|in:Cursando,Aprobada,Previa,Libre',
        ]);

        // Usamos updateExistingPivot para actualizar los campos en la tabla intermedia
        $alumno->submaterias()->updateExistingPivot($submateria->id, [
            'nota_final' => $request->nota_final,
            'estado' => $request->estado,
        ]);

        return back()->with('success', 'Información académica actualizada exitosamente.');
    }
}
