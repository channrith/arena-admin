<?php

use App\Http\Controllers\Api\VehicleMakerController;
use App\Http\Controllers\Api\CarModelController;
use App\Http\Controllers\Api\PosterController;
use App\Http\Middleware\ApiVehicleToken;
use Illuminate\Support\Facades\Route;

Route::middleware([ApiVehicleToken::class])->group(function () {
    Route::get('/vehicle-makers', [VehicleMakerController::class, 'index']);
    Route::get('/maker/{slug}/car-models', [CarModelController::class, 'indexByMaker']);
    Route::get('/car-models/{id}', [CarModelController::class, 'getModelSpecs']);
    Route::get('/vehicles/search', [CarModelController::class, 'search']);

    Route::get('/posters', [PosterController::class, 'index']);
});
