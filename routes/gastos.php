<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GastoController;

Route::middleware('auth')->group(function () {

    // Listado de gastos
    Route::get('/gastos', [GastoController::class, 'index'])->name('gastos.index');

    // Editar gasto
    Route::get('/gastos/{id}/edit', [GastoController::class, 'edit'])->name('gastos.edit');

    // Actualizar gasto
    Route::put('/gastos/{id}', [GastoController::class, 'update'])->name('gastos.update');

    // Eliminar gasto (en desarrollo)
    Route::delete('/gastos/{id}', function ($id) {
        return "Eliminar gasto $id - En desarrollo";
    })->name('gastos.destroy');

    // Guardar nuevo gasto (en desarrollo)
    Route::post('/gastos', function () {
        return "Guardar nuevo gasto - En desarrollo";
    })->name('gastos.store');
});