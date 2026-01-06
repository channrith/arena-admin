<?php

use App\Http\Controllers\Cars\CarModelColorController;
use App\Http\Controllers\Cars\CarModelController;
use App\Http\Controllers\Cars\CarModelImageController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')
    ->prefix('car-models')
    ->name('cars.models.')
    ->group(function () {
        Route::get('/', [CarModelController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [CarModelController::class, 'edit'])->name('edit');
        Route::get('/create', [CarModelController::class, 'create'])->name('add');
        Route::post('/', [CarModelController::class, 'store'])->name('store');
        Route::put('/{id}', [CarModelController::class, 'update'])->name('update');
        Route::delete('/{id}', [CarModelController::class, 'destroy'])->name('destroy');

        Route::get('{id}/colors/edit', [CarModelColorController::class, 'edit'])
            ->name('colors.edit');
        Route::put('{id}/colors', [CarModelColorController::class, 'update'])
            ->name('colors.update');

        Route::get('{id}/images/edit', [CarModelImageController::class, 'edit'])
            ->name('images.edit');
        Route::put('{id}/images', [CarModelImageController::class, 'update'])
            ->name('images.update');
    });
