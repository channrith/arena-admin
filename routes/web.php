<?php

use App\Http\Controllers\Cars\CarModelColorController;
use App\Http\Controllers\Cars\MakerController;
use App\Http\Controllers\Cars\CarModelController;
use App\Http\Controllers\Cars\CarModelImageController;
use App\Http\Controllers\Cars\VehicleTypeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PosterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VideoController;
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

Route::get('/vehicle-types', [VehicleTypeController::class, 'index'])->name('cars.types.index');
Route::get('/vehicle-types/{id}/edit', [VehicleTypeController::class, 'edit'])->name('cars.types.edit');
Route::get('/vehicle-types/create', [VehicleTypeController::class, 'create'])->name('cars.types.add');
Route::post('/vehicle-types', [VehicleTypeController::class, 'store'])->name('cars.types.store');
Route::put('/vehicle-types/{id}', [VehicleTypeController::class, 'update'])->name('cars.types.update');
Route::delete('/vehicle-types/{id}', [VehicleTypeController::class, 'destroy'])->name('cars.types.destroy');

Route::get('/car-models', [CarModelController::class, 'index'])->name('cars.models.index');
Route::get('/car-models/{id}/edit', [CarModelController::class, 'edit'])->name('cars.models.edit');
Route::get('/car-models/create', [CarModelController::class, 'create'])->name('cars.models.add');
Route::post('/car-models', [CarModelController::class, 'store'])->name('cars.models.store');
Route::put('/car-models/{id}', [CarModelController::class, 'update'])->name('cars.models.update');
Route::delete('/car-models/{id}', [CarModelController::class, 'destroy'])->name('cars.models.destroy');

Route::prefix('car-models')->name('cars.models.')->group(function () {
    Route::get('{id}/colors/edit', [CarModelColorController::class, 'edit'])
        ->name('colors.edit');

    Route::put('{id}/colors', [CarModelColorController::class, 'update'])
        ->name('colors.update');

    Route::get('{id}/images/edit', [CarModelImageController::class, 'edit'])
        ->name('images.edit');

    Route::put('{id}/images', [CarModelImageController::class, 'update'])
        ->name('images.update');
});

Route::get('/posters', [PosterController::class, 'index'])->name('posters.index');
Route::get('/posters/{id}/edit', [PosterController::class, 'edit'])->name('posters.edit');
Route::get('/posters/create', [PosterController::class, 'create'])->name('posters.add');
Route::post('/posters', [PosterController::class, 'store'])->name('posters.store');
Route::put('/posters/{id}', [PosterController::class, 'update'])->name('posters.update');
Route::delete('/posters/{id}', [PosterController::class, 'destroy'])->name('posters.destroy');

Route::get('/videos', [VideoController::class, 'index'])->name('videos.index');
Route::get('/videos/{id}/edit', [VideoController::class, 'edit'])->name('videos.edit');
Route::get('/videos/create', [VideoController::class, 'create'])->name('videos.add');
Route::post('/videos', [VideoController::class, 'store'])->name('videos.store');
Route::put('/videos/{id}', [VideoController::class, 'update'])->name('videos.update');
Route::delete('/videos/{id}', [VideoController::class, 'destroy'])->name('videos.destroy');
