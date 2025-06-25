<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Ruta raíz que redirige según estado de autenticación
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login.form');
});

// Incluir archivos con rutas separadas
require __DIR__ . '/authRoute.php';
require __DIR__ . '/dashboard.php';
require __DIR__ . '/usuarios.php';
require __DIR__ . '/profile.php';
require __DIR__ . '/alumnos.php';
require __DIR__ . '/cursos.php';
