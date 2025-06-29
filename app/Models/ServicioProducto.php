<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicioProducto extends Model
{
    protected $fillable = [
        'descripcion',
        'tipo',
        'idproveedor',
        'importe',
        'idsede',
        'idusuario',
    ];

    public $timestamps = true;

    // Relaciones si las necesitas más tarde
    // public function proveedor() { return $this->belongsTo(Proveedor::class, 'idproveedor'); }
    // public function sede() { return $this->belongsTo(Sede::class, 'idsede'); }
    // public function usuario() { return $this->belongsTo(User::class, 'idusuario'); }
}
