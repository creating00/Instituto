<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    protected $table = 'gastos';

    protected $fillable = [
        'idservicioproducto',
        'fecha',
        'total',
        'mes',
        'anio',
        'idsede',
        'idusuario',
    ];

    public function servicioProducto()
    {
        return $this->belongsTo(ServicioProducto::class, 'idservicioproducto');
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class, 'idsede');
    }
}
