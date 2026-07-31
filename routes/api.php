<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SignalementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\IncidentController;
use App\Http\Controllers\Api\DepartementController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/signalements', [SignalementController::class, 'store']);

    Route::get('/signalements', [SignalementController::class, 'index']);

    Route::get('/signalements/{signalement}', [SignalementController::class, 'show']);

    Route::put('/signalements/{signalement}', [SignalementController::class, 'update']);

    Route::patch('/signalements/{signalement}/status', [SignalementController::class, 'updateStatus']);
    Route::delete('/signalements/{signalement}', [SignalementController::class, 'destroy']);
        // Incidents

    Route::apiResource('incidents', IncidentController::class);

        // Départements

    Route::apiResource('departements', DepartementController::class);


});

