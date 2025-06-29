<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallePermiso extends Model
{
    protected $table = 'detalle_permisos';

    protected $fillable = [
        'id_permiso',
        'id_usuario',
    ];

    // Relaciones

    public function permiso()
    {
        return $this->belongsTo(Permiso::class, 'id_permiso');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}