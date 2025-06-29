<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    protected $table = 'examen';

    protected $fillable = [
        'idinscripcion',
        'interes',
        'total',
        'fecha',
        'mediodepago',
        'sede',
        'idusuario',
        'idcuota',
        'mes',
        'anio',
    ];

    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class, 'idinscripcion');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idusuario');
    }

    public function cuota()
    {
        return $this->belongsTo(Cuota::class, 'idcuota');
    }
}
