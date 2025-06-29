<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VentasUniforme extends Model
{
    protected $table = 'ventas_uniformes';

    public $timestamps = false;

    protected $fillable = [
        'id_uniforme',
        'id_alumno',
        'cantidad',
        'total',
        'fecha_venta',
        'medio_pago',
        'numero_factura',
        'vino_de_inscripcion',
        'precio_unitario',
        'id_usuario',
    ];

    // Relaciones ejemplo:
    public function uniforme()
    {
        return $this->belongsTo(Uniforme::class, 'id_uniforme', 'id');
    }

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'id_alumno', 'id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id');
    }
}
