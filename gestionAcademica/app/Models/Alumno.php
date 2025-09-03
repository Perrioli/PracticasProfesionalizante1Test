<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;
    protected $fillable = ['nombre', 'apellido', 'dni', 'fecha_nacimiento', 'escuela_origen', 'ano_ingreso'];

    public function documentaciones()
    {
        return $this->hasMany(Documentacion::class);
    }

    public function pases()
    {
        return $this->hasMany(Pase::class);
    }

    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'alumno_curso')
            ->withPivot('ano_lectivo', 'estado')
            ->withTimestamps();
    }

    public function modulos()
    {
        return $this->belongsToMany(Modulo::class, 'alumno_modulo')
            ->withPivot('estado', 'ano_lectivo')
            ->withTimestamps();
    }

    public function materias()
    {
        return $this->belongsToMany(Materia::class, 'alumno_materia')
            ->withPivot('nota_final', 'estado', 'fecha_evaluacion')
            ->withTimestamps();
    }
}
