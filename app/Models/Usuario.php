<?php
// app/Models/Usuario.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'usuario';

    protected $fillable = [
        'nombre',
        'correo',
        'usuario',
        'clave',
        'estado',
        'idsede',
        'idrol',
    ];

    protected $hidden = ['clave']; // ocultar clave al serializar

    public $timestamps = true;

    public function getAuthPassword()
    {
        return $this->clave;
    }

    public function getNameAttribute()
    {
        return $this->usuario;
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class, 'idsede');
    }
}
