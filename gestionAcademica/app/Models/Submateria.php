<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submateria extends Model
{
    use HasFactory;
    protected $fillable = ['nombre', 'carga_horaria'];

    public function materias()
    {
        return $this->belongsToMany(Materia::class, 'materia_submateria')->withTimestamps();
    }

    public function docentes()
    {
        return $this->belongsToMany(Docente::class, 'docente_submateria')
                    ->withPivot('ano_lectivo', 'turno')
                    ->withTimestamps();
    }

    public function alumnos()
    {
        return $this->belongsToMany(Alumno::class, 'alumno_submateria')
                    ->withPivot('nota_final', 'estado', 'fecha_evaluacion')
                    ->withTimestamps();
    }
}