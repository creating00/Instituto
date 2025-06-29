<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    protected $table = 'alumno';

    protected $fillable = [
        'dni',
        'nombre',
        'apellido',
        'direccion',
        'celular',
        'email',
        'tutor',
        'contacto',
        'idsede',
        'estado',
    ];

    // Relación con Sede (belongsTo)
    public function sede()
    {
        return $this->belongsTo(Sede::class, 'idsede', 'id')->withDefault();
    }
}
