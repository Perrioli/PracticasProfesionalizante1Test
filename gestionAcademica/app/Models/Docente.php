<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory;
    protected $fillable = ['nombre', 'apellido', 'dni', 'titulo', 'email'];

    public function submaterias()
    {
        return $this->belongsToMany(Submateria::class, 'docente_submateria')
                    ->withPivot('ano_lectivo', 'turno')
                    ->withTimestamps();
    }
}