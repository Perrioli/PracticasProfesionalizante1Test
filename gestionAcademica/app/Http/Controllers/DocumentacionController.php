<?php

namespace App\Http\Controllers;

use App\Models\Alumno; // ¡Añade este import!
use App\Models\Documentacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentacionController extends Controller
{
    /**
     * Muestra el formulario para crear un nuevo registro de documentación.
     */
    public function create(Alumno $alumno, Request $request)
    {
        // Obtenemos el tipo de documento del parámetro en la URL
        $tipoDoc = $request->query('tipo');
        return view('documentaciones.create', compact('alumno', 'tipoDoc'));
    }

    /**
     * Guarda el nuevo registro y el archivo.
     */
    public function store(Request $request, Alumno $alumno)
    {
        // 1. Validar los datos del formulario
        $request->validate([
            'tipo_documento' => 'required|string',
            'fecha_presentacion' => 'required|date',
            'estado' => 'required|in:Entregado,Pendiente',
            'archivo' => 'required|file|mimes:pdf,jpg,png,jpeg|max:2048' // max 2MB
        ]);

        // 2. Manejar la subida del archivo
        $filePath = $request->file('archivo')->store('documentos_alumnos/' . $alumno->id, 'public');
        $originalFilename = $request->file('archivo')->getClientOriginalName();

        // 3. Crear el registro en la base de datos usando la relación
        $alumno->documentaciones()->create([
            'tipo_documento' => $request->tipo_documento,
            'fecha_presentacion' => $request->fecha_presentacion,
            'estado' => $request->estado,
            'file_path' => $filePath,
            'original_filename' => $originalFilename
        ]);

        // 4. Redirigir de vuelta a la página de documentación del alumno
        return redirect()->route('alumnos.documentacion', $alumno)->with('success', 'Documento registrado y subido exitosamente.');
    }

    /**
     * Elimina un registro de documentación.
     */
    public function destroy(Documentacion $documentacion)
    {
        // Opcional: Eliminar el archivo físico del servidor antes de borrar el registro
        // Storage::disk('public')->delete($documentacion->file_path);

        $documentacion->delete();
        return back()->with('success', 'Registro de documentación eliminado exitosamente.');
    }

    public function verArchivo(Documentacion $documentacion)
    {
        // 1. Verificar que el registro tenga una ruta de archivo
        if (!$documentacion->file_path) {
            return back()->with('error', 'Este registro no tiene ningún archivo adjunto.');
        }

        // 2. Verificar que el archivo realmente exista en el almacenamiento
        if (!Storage::disk('public')->exists($documentacion->file_path)) {
            return back()->with('error', 'El archivo no fue encontrado en el servidor. Puede que haya sido eliminado.');
        }

        // 3. Devolver el archivo como una respuesta para que el navegador lo muestre
        return Storage::disk('public')->response($documentacion->file_path);
    }
}
