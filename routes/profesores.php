<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfesorController;

Route::middleware('auth')->group(function () {

    // Listado de profesores
    Route::get('/profesores', [ProfesorController::class, 'index'])->name('profesores.index');

    // Editar profesor
    Route::get('/profesores/{id}/edit', [ProfesorController::class, 'edit'])->name('profesores.edit');

    // Actualizar profesor
    Route::put('/profesores/{id}', [ProfesorController::class, 'update'])->name('profesors.update');

    // Eliminar profesor (en desarrollo)
    Route::delete('/profesores/{id}', function ($id) {
        return "Eliminar profesor $id - En desarrollo";
    })->name('profesores.destroy');

    // Guardar nuevo profesor (en desarrollo)
    Route::post('/profesores', function () {
        return "Guardar nuevo profesor - En desarrollo";
    })->name('profesores.store');
});