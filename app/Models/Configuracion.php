<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configuracion';

    protected $fillable = [
        'nombre',
        'telefono',
        'email',
        'direccion',
        'detalle_fac',
    ];
}
