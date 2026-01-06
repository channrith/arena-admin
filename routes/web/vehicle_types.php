<?php

use App\Http\Controllers\Cars\VehicleTypeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')
    ->prefix('vehicle-types')
    ->name('cars.types.')
    ->group(function () {
        Route::get('/', [VehicleTypeController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [VehicleTypeController::class, 'edit'])->name('edit');
        Route::get('/create', [VehicleTypeController::class, 'create'])->name('add');
        Route::post('/', [VehicleTypeController::class, 'store'])->name('store');
        Route::put('/{id}', [VehicleTypeController::class, 'update'])->name('update');
        Route::delete('/{id}', [VehicleTypeController::class, 'destroy'])->name('destroy');
    });
