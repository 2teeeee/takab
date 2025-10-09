<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/',  [MainController::class, 'index'])->name('main.index');
Route::get('/about',  [PageController::class, 'about'])->name('page.about');
Route::get('/contact',  [PageController::class, 'contact'])->name('page.contact');

Route::prefix('product')->group(function () {
    Route::get('/{id}/{slug}', [ProductController::class, 'view'])->name('product.view');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::get('/items', [CartController::class, 'show'])->name('cart.show');
    Route::post('/increase/{product}', [CartController::class, 'increase'])->name('cart.increase');
    Route::post('/decrease/{product}', [CartController::class, 'decrease'])->name('cart.decrease');
    Route::post('/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('cart.clear');

    Route::middleware('auth')->group(function () {
        Route::get('/address', [CartController::class, 'address'])->name('cart.address');
        Route::post('/pay', [CartController::class, 'pay'])->name('cart.pay');
    });
});

Route::get('/register', [AuthController::class, 'create'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.create');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::middleware(['role:admin,manager'])->group(function () {
        Route::resource('products', ProductController::class);
    });
});

