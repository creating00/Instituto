<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

Route::middleware('auth')->group(function () {

    // Listado de usuarios
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');

    // Editar usuario (en desarrollo)
    Route::get('/usuarios/{id}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');

    // Actualizar usuario
    Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');

    // Eliminar usuario (en desarrollo)
    Route::delete('/usuarios/{id}', function ($id) {
        return "Eliminar usuario $id - En desarrollo";
    })->name('usuarios.destroy');

    // Guardar nuevo usuario (en desarrollo)
    Route::post('/usuarios', function () {
        return "Guardar nuevo usuario - En desarrollo";
    })->name('usuarios.store');
});
