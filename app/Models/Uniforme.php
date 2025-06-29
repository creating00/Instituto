<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Uniforme extends Model
{
    protected $table = 'uniformes';

    protected $fillable = [
        'nombre',
        'descripcion',
        'talla',
        'genero',
        'precio',
        'stock',
        'estado',
    ];

    public $timestamps = true;
}
