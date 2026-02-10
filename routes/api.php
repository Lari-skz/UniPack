<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\EventController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes (no authentication required)
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {

    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
    });

    // Category routes
    Route::apiResource('categories', CategoryController::class);

    // Task routes
    Route::apiResource('tasks', TaskController::class);

    // Event routes
    Route::apiResource('events', EventController::class);
});

// Test route
Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'UniPack API is running!',
        'version' => '1.0.0',
        'timestamp' => now()->toISOString(),
    ]);
});
