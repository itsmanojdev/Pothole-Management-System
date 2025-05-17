<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisteredUserController;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Support\Facades\Route;

// Common Auth Routes
Route::middleware('auth')->prefix('user')->name('user.')->group(function () {
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/{user}', [ProfileController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/{user}', [ProfileController::class, 'update'])->middleware([HandlePrecognitiveRequests::class])->name('update');
        Route::patch('/{user}/password', [ProfileController::class, 'changePassword'])->middleware([HandlePrecognitiveRequests::class])->name('password');
        Route::delete('/{user}', [ProfileController::class, 'destroy'])->name('destroy');
    });
});


require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/citizen.php';
