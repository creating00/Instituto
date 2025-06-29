<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacturacionSecuencia extends Model
{
    protected $table = 'facturacion_secuencia';

    protected $fillable = [
        'anio',
        'secuencia',
    ];
}
