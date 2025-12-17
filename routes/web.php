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
    $featuredProducts = \App\Models\Product::where('is_featured', true)->take(3)->get();
    $slides = \App\Models\Slide::all();
    return view('home', compact('featuredProducts', 'slides'));
})->name('home');

// Static Pages
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/community', function () {
    $communities = \App\Models\Community::all();
    return view('community', compact('communities'));
})->name('community');

Route::get('/php-check', function () {
    return 'Upload Max: ' . ini_get('upload_max_filesize') . ' | Post Max: ' . ini_get('post_max_size');
});



Route::get('/archives', function () {
    $archives = \App\Models\Archive::all();
    return view('archives', compact('archives'));
})->name('archives');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

// Product Detail
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// Catalog (Dynamic)
// Uses ProductController if available, otherwise fallback to view
Route::get('/catalog', [ProductController::class, 'index'])->name('catalog');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/create', [App\Http\Controllers\AdminController::class, 'create'])->name('products.create');
    Route::post('/store', [App\Http\Controllers\AdminController::class, 'store'])->name('products.store');
    Route::get('/edit/{id}', [App\Http\Controllers\AdminController::class, 'edit'])->name('products.edit');
    Route::put('/update/{id}', [App\Http\Controllers\AdminController::class, 'update'])->name('products.update');
    Route::delete('/delete/{id}', [App\Http\Controllers\AdminController::class, 'destroy'])->name('products.destroy');
    Route::post('/feature/{id}', [App\Http\Controllers\AdminController::class, 'toggleFeatured'])->name('products.toggleFeatured');

    // Slides
    Route::post('/slides', [App\Http\Controllers\AdminController::class, 'storeSlide'])->name('slides.store');
    Route::get('/slides/{id}/edit', [App\Http\Controllers\AdminController::class, 'editSlide'])->name('slides.edit');
    Route::put('/slides/{id}', [App\Http\Controllers\AdminController::class, 'updateSlide'])->name('slides.update');
    Route::delete('/slides/{id}', [App\Http\Controllers\AdminController::class, 'destroySlide'])->name('slides.destroy');

    // Archives
    Route::post('/archives', [App\Http\Controllers\AdminController::class, 'storeArchive'])->name('archives.store');
    Route::get('/archives/{id}/edit', [App\Http\Controllers\AdminController::class, 'editArchive'])->name('archives.edit');
    Route::put('/archives/{id}', [App\Http\Controllers\AdminController::class, 'updateArchive'])->name('archives.update');
    Route::delete('/archives/{id}', [App\Http\Controllers\AdminController::class, 'destroyArchive'])->name('archives.destroy');

    // Categories
    Route::get('/categories', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('admin.categories.index');
    Route::post('/categories', [App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('admin.categories.store');
    Route::put('/categories/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('admin.categories.destroy');

    // Community
    Route::post('/communities', [App\Http\Controllers\AdminController::class, 'storeCommunity'])->name('communities.store');
    Route::delete('/communities/{id}', [App\Http\Controllers\AdminController::class, 'destroyCommunity'])->name('communities.destroy');
});
