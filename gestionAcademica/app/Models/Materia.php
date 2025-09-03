<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'carga_horaria_total',
        'modulo_id',
        'codigo',
        'regimen'
    ];

    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }

    public function alumnos()
    {
        return $this->belongsToMany(Alumno::class, 'alumno_materia')
            ->withPivot('nota_final', 'estado', 'fecha_evaluacion')
            ->withTimestamps();
    }

    public function docentes()
    {
        return $this->belongsToMany(Docente::class, 'docente_materia')
            ->withPivot('ano_lectivo', 'turno')
            ->withTimestamps();
    }

    public function prerequisites()
    {
        return $this->belongsToMany(Materia::class, 'materia_prerequisites', 'materia_id', 'prerequisite_id');
    }
}
