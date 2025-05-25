<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// include the admin routes
require_once __DIR__ . '/admin.php';

/*
 ********************** 
 * Define All Routes.
 **********************
*/

// Home Route
Route::get('/', [HomeController::class , 'index'])->name('home');

Route::get('/shop/{productSlug}', [ProductController::class , 'show'])->name('productShow');

// Auth Routes
Route::get('/login', [LoginController::class , 'login'])->name('login');
Route::post('/loginuser', [LoginController::class , 'loginUser']);
Route::get('/register', [RegisterController::class , 'register'])->name('register');