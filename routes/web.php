<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\auth\LogoutController;
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\Brand\BrandController;
use App\Http\Controllers\Product\SizeController;
use App\Http\Controllers\auth\RegisterController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Category\CategoryController;

Route::get('/', function () {
    return view('client.home');
})->name('home');
Route::get('/about', function () {
    return view('client.about');
});

Route::group(['middleware' => 'auth'], function () {
    Route::group(['middleware' => 'auth.client'], function () {
        // Route::get('/client', function () {
        //     return view('client.home');
        // })->name('home');
    });

    Route::group(['middleware' => 'auth.admin'], function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('indexAdmin');

        Route::get('/showCreateUser', [UserController::class, 'showCreateUser']);
        Route::post('/createUser', [UserController::class, 'createUser'])->name('createUser');
        Route::get('/showEditUser/{id}', [UserController::class, 'showEditUser']);
        Route::post('/editUser/{id}', [UserController::class, 'editUser'])->name('updateUser');

        Route::resource('products', ProductController::class);
        Route::resource('brands', BrandController::class);
        Route::resource('categorys', CategoryController::class);

        Route::get('addSize', [SizeController::class, 'index'])->name('product.size');
        Route::post('addSize', [SizeController::class, 'store'])->name('product.addSize');
        Route::put('updateSize/{id}', [SizeController::class, 'update'])->name('product.updateSize');
        Route::delete('deleteSize/{id}', [SizeController::class, 'delete'])->name('product.deleteSize');
    });

    Route::get('/logout', [LogoutController::class, 'logout'])->name('logout');
});


Route::get('/login', [LoginController::class, 'showForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('actionLogin');
Route::get('/register', [RegisterController::class, 'registerForm'])->name('registerForm');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::get('login/google', [SocialController::class, 'redirectToGoogle'])->name('login.google');
Route::get('login/google/callback', [SocialController::class, 'handleGoogleCallback']);

Route::get('/client', function () {
    return view('client.home');
})->name('home');

