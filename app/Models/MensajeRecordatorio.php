<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MensajeRecordatorio extends Model
{
    protected $table = 'mensaje_recordatorio';

    protected $fillable = [
        'contenidoMensaje',
        'estado',
        'fechaUltimaActualizacion',
    ];
}
