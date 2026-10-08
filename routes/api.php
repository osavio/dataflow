<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportController;

Route::get('/', function () {
    return response()->json('Welcome to the jungle!');
});

Route::apiResource('/imports', ImportController::class);