<?php

use App\Http\Controllers\PosterController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')
    ->prefix('posters')
    ->name('posters.')
    ->group(function () {
        Route::get('/', [PosterController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [PosterController::class, 'edit'])->name('edit');
        Route::get('/create', [PosterController::class, 'create'])->name('add');
        Route::post('/', [PosterController::class, 'store'])->name('store');
        Route::put('/{id}', [PosterController::class, 'update'])->name('update');
        Route::delete('/{id}', [PosterController::class, 'destroy'])->name('destroy');
    });
