<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SedeController;

Route::middleware('auth')->group(function () {

    // Listado de sedes
    Route::get('/sedes', [SedeController::class, 'index'])->name('sedes.index');

    // Editar sede
    Route::get('/sedes/{id}/edit', [SedeController::class, 'edit'])->name('sedes.edit');

    // Actualizar sede
    Route::put('/sedes/{id}', [SedeController::class, 'update'])->name('sedes.update');

    // Eliminar sede (en desarrollo)
    Route::delete('/sedes/{id}', function ($id) {
        return "Eliminar sede $id - En desarrollo";
    })->name('sedes.destroy');

    // Guardar nueva sede (en desarrollo)
    Route::post('/sedes', function () {
        return "Guardar nuevo sede - En desarrollo";
    })->name('sedes.store');
});