<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\LoginController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('Auth')->group(function () {
    Route::post('/register', [RegisterController::class, 'getUserData']);
    Route::post('/register/verify-code', [RegisterController::class, 'createUser']);

    //Login route
    Route::post('/login', [LoginController::class, 'getData']);
    Route::post('/login/verify-code', [LoginController::class, 'login']);
    
    //Logout route
    Route::delete('/logout/{token}', [LogoutController::class, 'logout']);
});