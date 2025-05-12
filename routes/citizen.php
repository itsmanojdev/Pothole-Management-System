<?php

use App\Http\Controllers\PotholeController;
use App\Models\Pothole;
use App\UserRole;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Citizen Routes
Route::middleware(['auth', 'role:' . UserRole::CITIZEN->value])->prefix('citizen')->name('citizen.')->group(function () {
    Route::view('dashboard', 'citizen.dashboard')->name('dashboard');

    Route::prefix('potholes')->name('pothole.')->group(function () {
        Route::get('/', [PotholeController::class, 'index'])->name('index');
        Route::get('create', [PotholeController::class, 'create'])->name('create');
        Route::post('/', [PotholeController::class, 'store'])->middleware([HandlePrecognitiveRequests::class]);
        Route::get('/{pothole}', [PotholeController::class, 'show'])->name('show');
        Route::get('/{pothole}/edit', [PotholeController::class, 'edit'])->name('edit');
        Route::patch('/{pothole}/verify', [PotholeController::class, 'verify'])->name('verify');
        Route::patch('/{pothole}', [PotholeController::class, 'update'])->middleware([HandlePrecognitiveRequests::class]);
        Route::delete('/{pothole}', [PotholeController::class, 'destroy'])->name('destroy');
    });
});
