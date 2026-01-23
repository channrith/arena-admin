<?php

use App\Http\Controllers\Api\VehicleTypeController;
use App\Http\Middleware\ApiVehicleToken;
use Illuminate\Support\Facades\Route;

Route::middleware([ApiVehicleToken::class])
    ->prefix('vehicle-types')
    ->group(function () {
        Route::get('/', [VehicleTypeController::class, 'index']);
    });
