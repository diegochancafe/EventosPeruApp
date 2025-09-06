<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\pages\HomePage;
use App\Http\Controllers\pages\CategoriesPage;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\pages\EventsPage;
use App\Http\Controllers\pages\ServicesPage;
use App\Http\Controllers\pages\UsersPage;

// Main Page Routes (accessible via JS after token login)
Route::get('/', [HomePage::class, 'index'])->name('pages-home');
Route::get('/page-services', [ServicesPage::class, 'index'])->name('pages-services');
Route::get('/page-events', [EventsPage::class, 'index'])->name('pages-events');
Route::get('/page-categories', [CategoriesPage::class, 'index'])->name('pages-categories');
Route::get('/page-users', [UsersPage::class, 'index'])->name('pages-users');


// Authentication pages (register/login views)
Route::get('/auth/login', [LoginBasic::class, 'index'])->name('login');
Route::get('/auth/register', [RegisterBasic::class, 'index'])->name('register');

// Error page
Route::get('/pages/misc-error', function () {
    return view('pages.misc-error');
})->name('pages-misc-error');
