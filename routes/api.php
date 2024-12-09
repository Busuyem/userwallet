<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware('check.ip')->group(function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function(){
        Route::get('/users', [UserController::class, 'listUsers']);
        Route::post('/transfer', [WalletController::class, 'transfer']);
        Route::get('/balance/{userId}', [WalletController::class, 'getBalance']);
        Route::post('/logout', [UserController::class, 'logout']);
    });
    
});

