<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use app\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// include the admin routes
require_once('./admin.php');

/*
 ********************** 
 * Define All Routes.
 **********************
*/

// Home Route
Route::get('/', [HomeController::class , 'index'])->name('home');

// Auth Routes
Route::get('login', [LoginController::class , 'login'])->name('login');
Route::get('register', [RegisterController::class , 'register'])->name('register');