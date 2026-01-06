<?php

use App\Http\Controllers\Cars\MakerController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')
    ->prefix('car-makers')
    ->name('cars.makers.')
    ->group(function () {
        Route::get('/', [MakerController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [MakerController::class, 'edit'])->name('edit');
        Route::get('/create', [MakerController::class, 'create'])->name('add');
        Route::post('/', [MakerController::class, 'store'])->name('store');
        Route::put('/{id}', [MakerController::class, 'update'])->name('update');
        Route::delete('/{id}', [MakerController::class, 'destroy'])->name('destroy');
    });
