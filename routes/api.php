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

    // Category routes
    Route::get('/category', [CategoryController::class, 'index']); // get categories
    Route::get('/category/{id}', [CategoryController::class, 'show']); // get category info
    Route::post('/category', [CategoryController::class, 'store']); // create category
    Route::put('/category/{id}', [CategoryController::class, 'update']); // update category
    Route::delete('/category/{id}', [CategoryController::class, 'destroy']); //

    // User routes
    Route::get('/users', [UserController::class, 'index']); // get user info
    Route::post('/user', [UserController::class, 'store']); // create user
    Route::get('/user/{id}', [UserController::class, 'show']); // get user info
    Route::put('/user/{id}', [UserController::class, 'update']); // actualizar
    Route::delete('/user/{id}', [UserController::class, 'destroy']); // eliminar

});
