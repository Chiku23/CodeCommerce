<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\Auth\LoginController;
use App\Http\Controllers\Shop\ProductController;
use App\Http\Controllers\Shop\Auth\RegisterController;
use App\Http\Controllers\Shop\Checkout\CheckoutController;

// include the admin routes
require_once __DIR__ . '/admin.php';

/*
 ********************** 
 * Define All Routes.
 **********************
*/

// Home Route
Route::get('/', [HomeController::class , 'index'])->name('home');

Route::prefix('shop')->name('shop.')->group(function () {
    // Auth Routes
    Route::get('/login', [LoginController::class , 'login'])->name('login');
    Route::post('/loginuser', [LoginController::class , 'loginUser']);
    Route::get('/register', [RegisterController::class , 'register'])->name('register');
    Route::post('/registeruser', [RegisterController::class , 'registerUser']);
    Route::get('/logout', [LoginController::class , 'logout'])->name('logout');

    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout Process
    Route::prefix('checkout')->name('checkout.')->group(function(){
        Route::get('/email', [CheckoutController::class, 'index'])->name('index');
        Route::post('/email-submit', [CheckoutController::class, 'emailSubmit'])->name('email');
        Route::get('/payment', [CheckoutController::class, 'payment'])->name('payment');
        Route::post('/payment/payment-process', [CheckoutController::class, 'createPaymentIntent'])->name('payment.process');
        Route::get('/thank-you', function () {
            return view('shop.checkout.thank-you');
        })->name('payment.success');
    });

    Route::get('/{productSlug}', [ProductController::class , 'show'])->name('productShow');
});