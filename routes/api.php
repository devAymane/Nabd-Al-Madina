<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SignalementController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
     Route::post('/signalements', [SignalementController::class, 'store']);
      Route::get('/signalements', [SignalementController::class, 'index']);
        Route::get('/signalements/{signalement}', [SignalementController::class, 'show']);
        Route::put('/signalements/{signalement}', [SignalementController::class, 'update']);
        Route::patch( '/signalements/{signalement}/status', [SignalementController::class, 'updateStatus']);
});