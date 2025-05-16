<?php

use App\Http\Controllers\AdminController;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Support\Facades\Route;

// Common Auth Routes
Route::middleware('auth')->prefix('user')->name('user.')->group(function () {
    Route::view('/profile', 'profile')->name('profile');
    Route::patch('/{user}/password', [AdminController::class, 'changePassword'])->middleware([HandlePrecognitiveRequests::class])->name('password');
    Route::delete('/{user}', [AdminController::class, 'destroy'])->name('delete');
});


require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/citizen.php';
