<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SearchController;

Route::get('/test', function() {
    return response()->json(['status' => 'OK']);
});

// Login
Route::post('/login', [AuthController::class, 'login']);

// Group middleware
Route::middleware('auth:sanctum')->group(function() {
    // CRUD Users
    Route::apiResource('/users', UserController::class);
    
    // Search Endpoints
    Route::get('/search/nama', [SearchController::class, 'searchByName']);
    Route::get('/search/nim', [SearchController::class, 'searchByNim']);
    Route::get('/search/ymd', [SearchController::class, 'searchByYmd']);
});