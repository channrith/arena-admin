<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/home');;
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::get('/posts/create', [PostController::class, 'create'])->name('posts.add');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::put('/posts/{id}', [PostController::class, 'update'])->name('posts.update');
Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
Route::get('/profile', [ProfileController::class, 'editProfile'])->name('profile.edit');
Route::post('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('profile.password.edit');
Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
