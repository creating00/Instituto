<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstadisticasController;

Route::middleware('auth')->group(function () {

    // Listado de salas
    Route::get('/estadisticas', [EstadisticasController::class, 'index'])->name('estadisticas.index');
});
