<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documentacion extends Model
{
    use HasFactory;

    protected $table = 'documentaciones';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'alumno_id',
        'tipo_documento',
        'fecha_presentacion',
        'estado',
        'file_path',
        'original_filename',
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }
}