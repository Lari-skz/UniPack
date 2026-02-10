<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\AdminController;

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

// Admin routes (require authentication + admin role)
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    // Dashboard statistics
    Route::get('/stats', [AdminController::class, 'stats']);

    // User management
    Route::get('/users', [AdminController::class, 'listUsers']);
    Route::get('/users/{id}', [AdminController::class, 'getUser']);
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser']);
    Route::patch('/users/{id}/toggle-admin', [AdminController::class, 'toggleAdminStatus']);

    // Recent activity
    Route::get('/recent-activity', [AdminController::class, 'recentActivity']);
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
