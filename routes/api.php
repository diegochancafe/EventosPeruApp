<?php

use App\Http\Controllers\api\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UserController;

// Register & Login
Route::post('/register', [UserController::class, 'register']); // register
Route::post('/login', [UserController::class, 'login']); // login

// Protected API routes (require token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [UserController::class, 'logout']); // logout
    Route::get('/category', [CategoryController::class, 'index']);

    Route::get('/user', [UserController::class, 'index']); // get user info
    Route::post('/user', [UserController::class, 'store']); // create user
});
