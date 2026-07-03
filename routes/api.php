<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\RegistrationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes – /api/v1/*
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // ─── Auth endpoints ──────────────────────────────────────────────────────
    Route::prefix('auth')->group(function () {
        Route::post('/login',    [AuthController::class, 'login']);    // M4.3
        Route::post('/register', [AuthController::class, 'register']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']); // Sanctum bonus
            Route::get('/me',     [AuthController::class, 'me']);      // Sanctum bonus
        });
    });

    // ─── Public event endpoints ───────────────────────────────────────────────
    Route::get('/events',       [EventController::class, 'index']); // M4.1
    Route::get('/events/{event}', [EventController::class, 'show']); // M4.2

    // ─── Protected endpoints (Sanctum token required) ────────────────────────
    Route::middleware('auth:sanctum')->group(function () {
        // M4.5 – List own registrations
        Route::get('/user/registrations', [RegistrationController::class, 'index']);

        // M4.4 – Register / cancel
        Route::post('/registrations',              [RegistrationController::class, 'store']);
        Route::delete('/registrations/{registration}', [RegistrationController::class, 'destroy']);
    });
});
