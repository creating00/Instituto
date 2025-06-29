<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalaController;

Route::middleware('auth')->group(function () {

    // Listado de salas
    Route::get('/salas', [SalaController::class, 'index'])->name('salas.index');

    // Editar sala
    Route::get('/salas/{id}/edit', [SalaController::class, 'edit'])->name('salas.edit');

    // Actualizar sala
    Route::put('/salas/{id}', [SalaController::class, 'update'])->name('salas.update');

    // Eliminar sala (en desarrollo)
    Route::delete('/salas/{id}', function ($id) {
        return "Eliminar sala $id - En desarrollo";
    })->name('salas.destroy');

    // Guardar nueva sala (en desarrollo)
    Route::post('/salas', function () {
        return "Guardar nuevo sala - En desarrollo";
    })->name('salas.store');
});