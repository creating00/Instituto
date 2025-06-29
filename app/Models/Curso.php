<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $table = 'curso';

    protected $fillable = [
        'nombre',
        'precio',
        'duracion',
        'estado',
        'tipo',
        'idsede',
        'dias',
        'inscripcion',
        'idprofesor',
        'monto',
        'fechaComienzo',
        'fechaTermino',
        'mora',
        'diasRecordatorio',
        'horarioDesde',
        'horarioHasta',
    ];

    // Relación con Sede (belongsTo)
    public function sede()
    {
        return $this->belongsTo(Sede::class, 'idsede', 'id')->withDefault();
    }
}
