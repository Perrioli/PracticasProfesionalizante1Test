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

    public function submaterias()
    {
        return $this->belongsToMany(Submateria::class, 'materia_submateria')->withTimestamps();
    }
    
    public function prerequisites()
    {
        return $this->belongsToMany(Materia::class, 'materia_prerequisites', 'materia_id', 'prerequisite_id');
    }
}