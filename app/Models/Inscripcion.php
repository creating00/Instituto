<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    protected $table = 'inscripcion';

    protected $fillable = [
        'idusuario',
        'idalumno',
        'idcurso',
        'idsala',
        'idprofesor',
        'fecha',
        'fechacomienzo',
        'importe',
        'mediodepago',
        'idsede',
        'estado',
        'mes',
        'anio',
        'fechaTermino',
        'isDetalle',
        'detalle',
        'nroFactura',
        'activo'
    ];

    public $timestamps = true;

    public function sede()
    {
        return $this->belongsTo(Sede::class, 'idsede')->where('estado', 1);
    }
}
