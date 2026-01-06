<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')
    ->prefix('profile')
    ->name('profile.')
    ->group(function () {
        Route::get('/', [ProfileController::class, 'editProfile'])->name('edit');
        Route::post('/', [ProfileController::class, 'updateProfile'])->name('update');
        Route::get('/password', [ProfileController::class, 'editPassword'])->name('password.edit');
        Route::post('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    });
