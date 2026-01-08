<?php

use App\Http\Controllers\Cars\VehicleSeriesController;
use Illuminate\Support\Facades\Route;

Route::get('/cars/series/by-maker/{maker}', function ($makerId) {
    return \App\Models\VehicleSeries::where('maker_id', $makerId)
        ->orderBy('name')
        ->get(['id', 'name']);
})->name('cars.series.byMaker');

Route::middleware('auth')
    ->prefix('vehicle-series')
    ->name('cars.series.')
    ->group(function () {
        Route::get('/', [VehicleSeriesController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [VehicleSeriesController::class, 'edit'])->name('edit');
        Route::get('/create', [VehicleSeriesController::class, 'create'])->name('add');
        Route::post('/', [VehicleSeriesController::class, 'store'])->name('store');
        Route::put('/{id}', [VehicleSeriesController::class, 'update'])->name('update');
        Route::delete('/{id}', [VehicleSeriesController::class, 'destroy'])->name('destroy');
    });
