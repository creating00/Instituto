<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuota extends Model
{
    protected $table = 'cuotas';

    protected $fillable = [
        'idinscripcion',
        'fecha',
        'cuota',
        'mes',
        'anio',
        'importe',
        'interes',
        'total',
        'condicion',
        'mediodepago',
        'idusuario',
        'idexamen',
        'isDetalle',
        'detalle',
        'tieneMora',
        'nroFactura',
    ];

    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class, 'id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id');
    }

    public function examen()
    {
        return $this->belongsTo(Examen::class, 'id');
    }
}
