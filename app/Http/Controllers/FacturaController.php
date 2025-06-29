<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class FacturaController extends Controller
{
    public function generarFactura()
    {
        $data = [
            'cliente' => 'Juan Pérez',
            'fecha' => date('d/m/Y'),
            'items' => [
                ['producto' => 'Curso de Laravel', 'cantidad' => 1, 'precio' => 120.00],
                ['producto' => 'Manual PDF', 'cantidad' => 2, 'precio' => 30.00],
            ],
        ];

        $pdf = Pdf::loadView('facturas.plantilla', $data);

        return $pdf->stream('factura.pdf'); // O ->download('factura.pdf');
    }
}
