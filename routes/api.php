<?php

use App\Http\Controllers\Api\ApiMultimediaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VideosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Public routes
Route::get('/multimedia', [ApiMultimediaController::class, 'index']); // Public access to index
Route::get('/multimedia/{id}', [ApiMultimediaController::class, 'show']); // Public access to show

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Keep other multimedia routes protected
    Route::apiResource('multimedia', ApiMultimediaController::class)->except(['index', 'show']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    // Rutes antigues (opcional si es vol mantenir compatibilitat)
    Route::post('/videos', [VideosController::class, 'apiStore']);
    Route::get('/videos', [VideosController::class, 'apiIndex']);
    Route::get('/video/{id}', [VideosController::class, 'apiShow']);
    Route::get('/users', [UsersController::class, 'apiIndex']);
    Route::get('/users/{id}', [UsersController::class, 'apiShow']);
});
