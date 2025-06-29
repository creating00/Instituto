<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InscripcionController;

Route::middleware('auth')->group(function () {

    // Listado de inscripciones
    Route::get('/inscripciones', [InscripcionController::class, 'index'])->name('inscripciones.index');

    // Editar inscripcion
    Route::get('/inscripciones/{id}/edit', [InscripcionController::class, 'edit'])->name('inscripciones.edit');

    // Actualizar inscripcion
    Route::put('/inscripciones/{id}', [InscripcionController::class, 'update'])->name('inscripciones.update');

    // Eliminar inscripcion (en desarrollo)
    Route::delete('/inscripciones/{id}', function ($id) {
        return "Eliminar inscripcion $id - En desarrollo";
    })->name('inscripciones.destroy');

    // Guardar nuevo inscripcion (en desarrollo)
    Route::post('/inscripciones', function () {
        return "Guardar nueva inscripcion - En desarrollo";
    })->name('inscripciones.store');
});
