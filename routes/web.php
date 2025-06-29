<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FacturaController;
// Ruta raíz que redirige según estado de autenticación
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login.form');
});



Route::get('/factura/pdf', [FacturaController::class, 'generarFactura']);

// Incluir archivos con rutas separadas
require __DIR__ . '/authRoute.php';
require __DIR__ . '/dashboard.php';
require __DIR__ . '/usuarios.php';
require __DIR__ . '/profile.php';
require __DIR__ . '/alumnos.php';
require __DIR__ . '/cursos.php';
require __DIR__ . '/profesores.php';
require __DIR__ . '/salas.php';
require __DIR__ . '/sedes.php';
require __DIR__ . '/ganancias.php';
require __DIR__ . '/config.php';
require __DIR__ . '/inscripcion.php';
require __DIR__ . '/cuotas.php';
require __DIR__ . '/estadisticas.php';
require __DIR__ . '/gastos.php';
