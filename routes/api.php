<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return response()->json('Welcome to the jungle!');
});

Route::apiResource('/users', UserController::class);
Route::apiResource('/imports', ImportController::class);