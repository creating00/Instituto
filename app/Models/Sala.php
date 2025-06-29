<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sala extends Model
{
    protected $table = 'sala';

    protected $fillable = [
        'sala',
        'descripcion',
        'estado',
    ];
}