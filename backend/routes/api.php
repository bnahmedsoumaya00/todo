<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protect these routes with Sanctum auth middleware
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('todos', TodoController::class);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/dashboard',[AdminController::class, 'dashboard']);
    Route::get('/statistics', [AdminController::class, 'statistics']);
    Route::put('/users/{id}/role', [AdminController::class, 'updateUserRole']);
});
