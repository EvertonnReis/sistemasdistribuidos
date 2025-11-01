<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes - requer autenticação JWT
Route::middleware('auth.jwt')->group(function () {
    // Auth endpoints
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me', [AuthController::class, 'me']);

    // Course endpoints - CRUD completo
    Route::prefix('courses')->group(function () {
        Route::get('/', [CourseController::class, 'index']);
        Route::get('/{id}', [CourseController::class, 'show']);
        Route::post('/', [CourseController::class, 'store']);
        Route::put('/{id}', [CourseController::class, 'update']);
        Route::delete('/{id}', [CourseController::class, 'destroy']);
    });

    // Lesson endpoints - CRUD completo
    Route::prefix('lessons')->group(function () {
        Route::get('/', [LessonController::class, 'index']);
        Route::get('/{id}', [LessonController::class, 'show']);
        Route::post('/', [LessonController::class, 'store']);
        Route::put('/{id}', [LessonController::class, 'update']);
        Route::delete('/{id}', [LessonController::class, 'destroy']);
    });
});
