<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MasterController;

// Страницы для всех
Route::get('/', [FrontController::class, 'index'])->name('home');
Route::get('/category/{slug}', [FrontController::class, 'category'])->name('category.show');

// Запись на мастер-класс (требует авторизации)
Route::middleware('auth')->group(function () {
    Route::get('/booking/confirm/{id}', [FrontController::class, 'confirmBooking'])->name('booking.confirm');
    Route::post('/booking/store/{id}', [FrontController::class, 'storeBooking'])->name('booking.store');
});

// Аутентификация
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Кабинет ведущего
Route::middleware(['auth', 'role:master'])->prefix('master')->name('master.')->group(function () {
    Route::get('/dashboard', [MasterController::class, 'dashboard'])->name('dashboard');
    Route::get('/classes/create', [MasterController::class, 'createClass'])->name('classes.create');
    Route::post('/classes', [MasterController::class, 'storeClass'])->name('classes.store');
    Route::put('/classes/{id}', [MasterController::class, 'updateClass'])->name('classes.update');
});