<?php

use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')
    ->prefix('videos')
    ->name('videos.')
    ->group(function () {
        Route::get('/', [VideoController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [VideoController::class, 'edit'])->name('edit');
        Route::get('/create', [VideoController::class, 'create'])->name('add');
        Route::post('/', [VideoController::class, 'store'])->name('store');
        Route::put('/{id}', [VideoController::class, 'update'])->name('update');
        Route::delete('/{id}', [VideoController::class, 'destroy'])->name('destroy');
    });
