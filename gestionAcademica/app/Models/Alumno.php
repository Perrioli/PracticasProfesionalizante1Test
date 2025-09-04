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

    public function haAprobadoCorrelativas(Materia $materia): bool
    {
        $correlativasIds = $materia->prerequisites()->pluck('materias.id');

        if ($correlativasIds->isEmpty()) {
            return true;
        }

        $materiasAprobadasIds = $this->materias()
            ->wherePivot('estado', 'Aprobada')
            ->pluck('materias.id');

        $correlativasFaltantes = $correlativasIds->diff($materiasAprobadasIds);

        return $correlativasFaltantes->isEmpty();
    }

    public function haAprobadoModulo(Modulo $modulo): bool
    {
        $materiasDelModuloIds = $modulo->materias()->pluck('id');

        if ($materiasDelModuloIds->isEmpty()) {
            return true;
        }

        $materiasAprobadasCount = $this->materias()
            ->whereIn('materia_id', $materiasDelModuloIds)
            ->wherePivot('estado', 'Aprobada')
            ->count();

        return $materiasAprobadasCount === $materiasDelModuloIds->count();
    }
}
