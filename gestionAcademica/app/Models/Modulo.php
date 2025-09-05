<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 
        'orden', 
        'ano_correspondiente', 
        'plan_estudio_id',
        'prerequisite_modulo_id' // Asegúrate de que este campo esté aquí si lo usas
    ];

    public function planEstudio()
    {
        return $this->belongsTo(PlanEstudio::class);
    }

    public function materias()
    {
        return $this->hasMany(Materia::class);
    }
    
    public function prerequisite()
    {
        return $this->belongsTo(Modulo::class, 'prerequisite_modulo_id');
    }

    public function cursos()
    {
        return $this->hasMany(Curso::class);
    }
}