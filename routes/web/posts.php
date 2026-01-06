<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')
    ->prefix('posts')
    ->name('posts.')
    ->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [PostController::class, 'edit'])->name('edit');
        Route::get('/create', [PostController::class, 'create'])->name('add');
        Route::post('/', [PostController::class, 'store'])->name('store');
        Route::put('/{id}', [PostController::class, 'update'])->name('update');
        Route::delete('/{id}', [PostController::class, 'destroy'])->name('destroy');
    });
