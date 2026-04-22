<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\PersonalAccessToken;

//route public
Route::post('/login', [AuthController::class, 'login']);
Route::get('/profiles', [ProfileController::class, 'indexPublic']);

// route sous authentification

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/profile/{profile}', [ProfileController::class, 'update']);
    Route::delete('/profile/{profile}', [ProfileController::class, 'delete']);
    Route::post('/profile', [ProfileController::class, 'store']);
    Route::get('/profiles/list', [ProfileController::class, 'indexAdmin']);
});