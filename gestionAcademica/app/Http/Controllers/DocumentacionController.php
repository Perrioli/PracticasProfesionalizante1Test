<?php

namespace App\Http\Controllers;

use App\Models\Alumno; // ¡Añade este import!
use App\Models\Documentacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentacionController extends Controller
{
    public function create(Alumno $alumno, Request $request)
    {

        $tipoDoc = $request->query('tipo');
        return view('documentaciones.create', compact('alumno', 'tipoDoc'));
    }

    public function store(Request $request, Alumno $alumno)
    {
        $request->validate([
            'tipo_documento' => 'required|string',
            'fecha_presentacion' => 'required|date',
            'estado' => 'required|in:Entregado,Pendiente',
            'archivo' => 'required|file|mimes:pdf,jpg,png,jpeg|max:2048'
        ]);

        $filePath = $request->file('archivo')->store('documentos_alumnos/' . $alumno->id, 'public');
        $originalFilename = $request->file('archivo')->getClientOriginalName();

        $alumno->documentaciones()->create([
            'tipo_documento' => $request->tipo_documento,
            'fecha_presentacion' => $request->fecha_presentacion,
            'estado' => $request->estado,
            'file_path' => $filePath,
            'original_filename' => $originalFilename
        ]);

        return redirect()->route('alumnos.documentacion', $alumno)->with('success', 'Documento registrado y subido exitosamente.');
    }

    public function destroy(Documentacion $documentacion)
    {


        $documentacion->delete();
        return back()->with('success', 'Registro de documentación eliminado exitosamente.');
    }

    public function verArchivo(Documentacion $documentacion)
    {
        if (!$documentacion->file_path) {
            return back()->with('error', 'Este registro no tiene ningún archivo adjunto.');
        }
        if (!Storage::disk('public')->exists($documentacion->file_path)) {
            return back()->with('error', 'El archivo no fue encontrado en el servidor. Puede que haya sido eliminado.');
        }

        return Storage::disk('public')->response($documentacion->file_path);
    }
}
