<?php

use App\Http\Controllers\Cars\MakerController;
use App\Http\Controllers\Cars\CarModelController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PosterController;
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

Route::get('/car-makers', [MakerController::class, 'index'])->name('cars.makers.index');
Route::get('/car-makers/{id}/edit', [MakerController::class, 'edit'])->name('cars.makers.edit');
Route::get('/car-makers/create', [MakerController::class, 'create'])->name('cars.makers.add');
Route::post('/car-makers', [MakerController::class, 'store'])->name('cars.makers.store');
Route::put('/car-makers/{id}', [MakerController::class, 'update'])->name('cars.makers.update');
Route::delete('/car-makers/{id}', [MakerController::class, 'destroy'])->name('cars.makers.destroy');

Route::get('/car-models', [CarModelController::class, 'index'])->name('cars.models.index');
Route::get('/car-models/{id}/edit', [CarModelController::class, 'edit'])->name('cars.models.edit');
Route::get('/car-models/create', [CarModelController::class, 'create'])->name('cars.models.add');
Route::post('/car-models', [CarModelController::class, 'store'])->name('cars.models.store');
Route::put('/car-models/{id}', [CarModelController::class, 'update'])->name('cars.models.update');
Route::delete('/car-models/{id}', [CarModelController::class, 'destroy'])->name('cars.models.destroy');

Route::get('/posters', [PosterController::class, 'index'])->name('posters.index');
Route::get('/posters/{id}/edit', [PosterController::class, 'edit'])->name('posters.edit');
Route::get('/posters/create', [PosterController::class, 'create'])->name('posters.add');
Route::post('/posters', [PosterController::class, 'store'])->name('posters.store');
Route::put('/posters/{id}', [PosterController::class, 'update'])->name('posters.update');
Route::delete('/posters/{id}', [PosterController::class, 'destroy'])->name('posters.destroy');