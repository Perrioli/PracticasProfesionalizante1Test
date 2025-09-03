<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory;
    protected $fillable = ['nombre', 'apellido', 'dni', 'titulo', 'email'];

    public function materias()
    {
        return $this->belongsToMany(Materia::class, 'docente_materia')
            ->withPivot('ano_lectivo', 'turno')
            ->withTimestamps();
    }
}
