<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home Page
// Auth Routes
Route::get('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login')->middleware('guest');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'authenticate']);
Route::get('/register', [App\Http\Controllers\AuthController::class, 'register'])->name('register')->middleware('guest');
Route::post('/register', [App\Http\Controllers\AuthController::class, 'store']);
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/', function () {
    return view('home');
})->name('home');

// Static Pages
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/community', function () {
    return view('community');
})->name('community');

Route::get('/archives', function () {
    return view('archives');
})->name('archives');

// Product Detail
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// Catalog (Dynamic)
// Uses ProductController if available, otherwise fallback to view
Route::get('/catalog', [ProductController::class, 'index'])->name('catalog');

// Cart & Checkout
Route::get('/cart', function () {
    // Return cart view
    return view('cart');
})->name('cart');

Route::get('/checkout', function () {
    return view('checkout');
})->name('checkout');


