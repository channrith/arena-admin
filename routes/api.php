<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'namespace' => 'Api'], function () {
    require __DIR__ . '/api/post.php';
    require __DIR__ . '/api/vehicle.php';
    require __DIR__ . '/api/video.php';
});
