<?php

use App\AdminFilter;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PotholeController;
use App\Models\User;
use App\UserRole;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Admin & Super Admin Routes
Route::middleware(['auth', 'role:' . UserRole::ADMIN->value])->prefix('admin')->name('admin.')->group(function () {
    Route::view('dashboard', 'admin.dashboard')->name('dashboard');

    Route::prefix('potholes')->name('pothole.')->group(function () {
        Route::get('/', [PotholeController::class, 'index'])->name('index');
        Route::get('/{pothole}', [PotholeController::class, 'show'])->name('show');
        Route::patch('/{pothole}/status', [PotholeController::class, 'statusUpdate'])->name('status');
    });

    Route::middleware('role:' . UserRole::SUPER_ADMIN->value)->group(function () {
        Route::prefix('management')->name('management.')->group(function () {
            Route::get('/index', [AdminController::class, 'index'])->name('index');
            Route::get('/create', [AdminController::class, 'create'])->name('create');
            Route::post('/', [AdminController::class, 'store'])->middleware([HandlePrecognitiveRequests::class])->name('store');
            Route::get('/{admin}', [AdminController::class, 'show'])->name('show');
            Route::get('/{admin}/edit', [AdminController::class, 'edit'])->name('edit');
            Route::patch('/{user}/password', [AdminController::class, 'changePassword'])->middleware([HandlePrecognitiveRequests::class])->name('password');
            Route::patch('/{user}', [AdminController::class, 'update'])->middleware([HandlePrecognitiveRequests::class])->name('update');
            Route::delete('/{user}', [AdminController::class, 'destroy'])->name('destroy');
        });

        Route::patch('potholes/{pothole}/verify', [PotholeController::class, 'verify'])->name('pothole.verify');
    });
});
