<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GananciasController;

Route::middleware('auth')->group(function () {

    // Listado de salas
    Route::get('/ganancias', [GananciasController::class, 'index'])->name('ganancias.index');
});
