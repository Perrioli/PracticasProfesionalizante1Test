<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}