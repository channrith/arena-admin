<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/home');;
});

// registers authentication routes (e.g. /login, /register, /logout etc.)
Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});

require __DIR__ . '/web/car_makers.php';
require __DIR__ . '/web/car_models.php';
require __DIR__ . '/web/posts.php';
require __DIR__ . '/web/posters.php';
require __DIR__ . '/web/vehicle_series.php';
require __DIR__ . '/web/vehicle_types.php';
require __DIR__ . '/web/videos.php';