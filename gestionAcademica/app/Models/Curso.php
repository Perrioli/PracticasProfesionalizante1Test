<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Horario;

class Curso extends Model
{
    use HasFactory;
    protected $fillable = ['nombre', 'turno', 'ano_lectivo', 'modulo_id'];

    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }

    public function alumnos()
    {
        return $this->belongsToMany(Alumno::class, 'alumno_curso')
            ->withPivot('ano_lectivo', 'estado')
            ->withTimestamps();
    }

    public function materias()
    {
        return $this->belongsToMany(Materia::class, 'curso_materia_docente')
            ->withPivot('docente_id', 'dia_semana', 'hora_inicio', 'hora_fin');
    }

    public function docentes()
    {
        return $this->belongsToMany(Docente::class, 'curso_materia_docente')
            ->withPivot('materia_id', 'dia_semana', 'hora_inicio', 'hora_fin');
    }

     public function horario()
    {
        return $this->hasMany(Horario::class);
    }
}
