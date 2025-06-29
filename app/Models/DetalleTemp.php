<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleTemp extends Model
{
    protected $table = 'detalle_temp';

    protected $fillable = [
        'id_usuario',
        'id_producto',
        'cantidad',
        'precio_venta',
        'total',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
}