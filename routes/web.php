<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
    Route::get('/signUp', [\App\Http\Controllers\UserController::class, 'getSignUpForm'])->name('signUp');
    Route::post('/signUp', [\App\Http\Controllers\UserController::class, 'signUp'])->name('post.signUp');

    Route::get('/login', [\App\Http\Controllers\UserController::class, 'getLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\UserController::class, 'login'])->name('post.login');

Route::middleware('auth')->group(function () {
    Route::get('/catalog', [\App\Http\Controllers\ProductController::class, 'getCatalog'])->name('catalog');
    Route::post('/add', [\App\Http\Controllers\ProductController::class, 'addProduct'])->name('add.product');
    Route::post('/decrease', [\App\Http\Controllers\ProductController::class, 'decreaseProduct'])->name('decrease.product');

    Route::get('/product/{id}', [\App\Http\Controllers\ProductController::class, 'getProduct'])->name('product');
    Route::post('/product/{id}', [\App\Http\Controllers\ProductController::class, 'addReview'])->name('add.review');

    Route::get('/cart', [\App\Http\Controllers\CartController::class, 'getCart'])->name('cart');

    Route::get('/logout', [\App\Http\Controllers\UserController::class, 'logout'])->name('logout');

    Route::get('/profile', [\App\Http\Controllers\UserController::class, 'getProfile'])->name('profile');

    Route::get('/editProfile', [\App\Http\Controllers\UserController::class, 'getEditProfile'])->name('editProfile');
    Route::post('/editProfile', [\App\Http\Controllers\UserController::class, 'editProfile'])->name('post.editProfile');

    Route::get('/create-order', [\App\Http\Controllers\OrderController::class, 'getOrder'])->name('createOrder');
    Route::post('/create-order', [\App\Http\Controllers\OrderController::class, 'createOrder'])->name('post.createOrder');

    Route::get('/user-order', [\App\Http\Controllers\OrderController::class, 'getUserOrder'])->name('userOrder');
});








