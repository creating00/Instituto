<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recordatorio extends Model
{
    protected $table = 'recordatorios';

    protected $fillable = [
        'id_curso',
        'fecha_recordatorio',
        'estado',
        'id_alumno',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso', 'id');
    }

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'id_alumno', 'id');
    }
}
