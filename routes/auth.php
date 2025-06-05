<?php

use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionController;
use App\UserRole;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::middleware('guest')->group(function () {
    Route::view('/', 'home')->name('home');
    Route::view('/about', 'about')->name('about');
    Route::view('/contact', 'contact')->name('contact');

    Route::get('/login', [SessionController::class, 'create'])->name('login');
    Route::post('/login', [SessionController::class, 'store'])->middleware([HandlePrecognitiveRequests::class])->name('login.store');

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->middleware([HandlePrecognitiveRequests::class])->name('register.store');
});

Route::middleware('role:' . UserRole::MK->value)->prefix("db")->name('db.')->group(function () {
    Route::get('/user', [DatabaseController::class, 'getUsers'])->name('user');
    Route::get('/pothole', [DatabaseController::class, 'getPotholes'])->name('pothole');
});

Route::post('/logout', [SessionController::class, 'destroy'])->name('logout');
Route::get('/ping', fn() => response()->noContent());
