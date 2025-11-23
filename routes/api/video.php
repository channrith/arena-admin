<?php

use App\Http\Controllers\Api\VideoController;
use App\Http\Middleware\ApiPostToken;
use Illuminate\Support\Facades\Route;

Route::middleware([ApiPostToken::class])->group(function () {
    Route::get('/videos', [VideoController::class, 'index']);
});
