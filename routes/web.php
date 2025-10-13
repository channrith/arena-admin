<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/home');;
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/news', [NewsController::class, 'index'])->name('news.list');
Route::get('/news/create', [NewsController::class, 'create'])->name('news.add');
Route::post('/news', [NewsController::class, 'store'])->name('news.store');

