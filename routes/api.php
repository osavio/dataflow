<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return response()->json('Welcome to the jungle!');
});

Route::apiResource('/users', UserController::class);
Route::post('/login', [AuthController::class, 'login']);
Route::apiResource('/imports', ImportController::class)->middleware('auth:sanctum');