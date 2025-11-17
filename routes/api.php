<?php

use App\Http\Controllers\api\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\ServiceController;
use App\Http\Controllers\api\EventController;
use App\Http\Controllers\api\HomeController;
use App\Http\Controllers\api\RatingController;

// Register & Login
Route::post('/register', [UserController::class, 'register']); // register
Route::post('/login', [UserController::class, 'login']); // login

// Protected API routes (require token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [UserController::class, 'logout']); // logout

    // Home routes
    Route::get('/home/services-usage', [HomeController::class, 'getServicesUsage']); // get services usage statistics
    Route::get('/home/events-total-by-status', [HomeController::class, 'getEventsTotalByStatus']); // get events total by status statistics

    // Rating routes
    Route::get('/ratings', [RatingController::class, 'index']);
    Route::get('/rating/{id}', [RatingController::class, 'show']);
    Route::post('/rating', [RatingController::class, 'store']);
    Route::put('/rating/{id}', [RatingController::class, 'update']);
    Route::delete('/rating/{id}', [RatingController::class, 'destroy']);

    // Category routes
    Route::get('/categories', [CategoryController::class, 'index']); // get categories
    Route::get('/categories/services', [CategoryController::class, 'indexWithServices']); // get categories
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

    // Service routes
    Route::get('/services', [ServiceController::class, 'index']); // get services
    Route::get('/services/all', [ServiceController::class, 'listForEvents']); // get services
    Route::get('/service/{id}', [ServiceController::class, 'show']); // get service info
    Route::post('/services', [ServiceController::class, 'store']); // create service
    Route::put('/service/{id}', [ServiceController::class, 'update']); // update service
    Route::delete('/service/{id}', [ServiceController::class, 'destroy']); // delete service

    // Event routes
    Route::get('/events', [EventController::class, 'index']); // get events
    Route::get('/event/{id}', [EventController::class, 'show']); // get event info
    Route::post('/event', [EventController::class, 'store']); // create event
    Route::put('/event/{id}', [EventController::class, 'update']); // update event
    Route::delete('/event/{id}', [EventController::class, 'destroy']); // delete event
    Route::get('/events/{id}/pdf', [EventController::class, 'downloadPdf']); // download event PDF
});
