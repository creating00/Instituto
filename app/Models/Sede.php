<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    protected $table = 'sedes';

    protected $fillable = [
        'nombre',
        'provincia',
        'ciudad',
        'direccion',
        'email',
        'telefono',
        'estado',
    ];
}