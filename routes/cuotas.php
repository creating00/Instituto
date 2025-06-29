<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CuotaController;

Route::middleware('auth')->group(function () {
    Route::get('/cuotas', [CuotaController::class, 'index'])->name('cuotas.index')->middleware('auth');
});
