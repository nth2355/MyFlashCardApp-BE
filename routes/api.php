<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Public auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Protected
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', fn (Request $request) => response()->json([
        'user' => $request->user(),
    ]));
});

