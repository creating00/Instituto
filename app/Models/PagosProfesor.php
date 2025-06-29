<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagosProfesor extends Model
{
    protected $table = 'pagosprofesores';

    protected $fillable = [
        'idprofesor',
        'idsede',
        'importe',
        'fecha',
        'mes',
        'anio',
        'descripcion',
    ];
}
