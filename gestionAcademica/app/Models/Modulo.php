<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    use HasFactory;
    protected $fillable = ['nombre', 'orden', 'ano_correspondiente', 'plan_estudio_id'];

    public function planEstudio()
    {
        return $this->belongsTo(PlanEstudio::class);
    }

    public function materias()
    {
        return $this->hasMany(Materia::class);
    }

    public function alumnos()
    {
        return $this->belongsToMany(Alumno::class, 'alumno_modulo')
                    ->withPivot('estado', 'ano_lectivo')
                    ->withTimestamps();
    }
}
