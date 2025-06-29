<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CursoController;

Route::middleware('auth')->group(function () {

    // Listado de alumnos
    Route::get('/cursos', [CursoController::class, 'index'])->name('cursos.index');

    // Editar alumno
    Route::get('/cursos/{id}/edit', [CursoController::class, 'edit'])->name('cursos.edit');

    // Actualizar alumno
    Route::put('/cursos/{id}', [CursoController::class, 'update'])->name('alumnos.update');

    // Eliminar alumno (en desarrollo)
    Route::delete('/cursos/{id}', function ($id) {
        return "Eliminar alumno $id - En desarrollo";
    })->name('cursos.destroy');

    // Guardar nuevo alumno (en desarrollo)
    Route::post('/cursos', function () {
        return "Guardar nuevo alumno - En desarrollo";
    })->name('cursos.store');

    Route::get('/cursos/lista', [CursoController::class, 'lista']);
});
