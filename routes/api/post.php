<?php

use App\Http\Controllers\Api\PostController;
use App\Http\Middleware\ApiPostToken;
use Illuminate\Support\Facades\Route;

Route::middleware([ApiPostToken::class])->group(function () {
    Route::get('/highlights', [PostController::class, 'getTopHighlight']);
    Route::get('/posts', [PostController::class, 'getAllPosts']);
    Route::get('/posts/{id}', [PostController::class, 'getPostDetailById']);
});
