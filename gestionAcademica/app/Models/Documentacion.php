<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documentacion extends Model
{
    use HasFactory;
    protected $fillable = ['alumno_id', 'tipo_documento', 'fecha_presentacion', 'estado'];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }
}