<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConfiguracionController;

Route::middleware('auth')->group(function () {
    Route::get('/config', [ConfiguracionController::class, 'index'])->name('config.index');
    Route::put('/config', [ConfiguracionController::class, 'update'])->name('configuracion.update');
    Route::post('/config/mensaje', [ConfiguracionController::class, 'guardarMensaje'])->name('configuracion.guardarMensaje');
});
