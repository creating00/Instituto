<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\AlumnoHistorialController;


Route::middleware('auth')->group(function () {

    // Listado de alumnos
    Route::get('/alumnos', [AlumnoController::class, 'index'])->name('alumnos.index');

    // Editar alumno
    Route::get('/alumnos/{id}/edit', [AlumnoController::class, 'edit'])->name('alumnos.edit');

    // Actualizar alumno
    Route::put('/alumnos/{id}', [AlumnoController::class, 'update'])->name('alumnos.update');

    // Eliminar alumno (en desarrollo)
    Route::delete('/alumnos/{id}', function ($id) {
        return "Eliminar alumno $id - En desarrollo";
    })->name('alumnos.destroy');

    // Guardar nuevo alumno (en desarrollo)
    Route::post('/alumnos', function () {
        return "Guardar nuevo alumno - En desarrollo";
    })->name('alumnos.store');

    Route::get('/historial-alumno', [AlumnoHistorialController::class, 'index'])->name('alumnos.historial');
});
