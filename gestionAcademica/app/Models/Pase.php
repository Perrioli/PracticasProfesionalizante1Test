<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pase extends Model
{
    use HasFactory;
    protected $fillable = ['alumno_id', 'institucion_origen', 'fecha_pase', 'observaciones'];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }
}