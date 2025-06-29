<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profesor extends Model
{
    protected $table = 'profesor';

    protected $fillable = [
        'dni',
        'nombre',
        'apellido',
        'direccion',
        'celular',
        'email',
        'idsede',
        'estado',
    ];
}