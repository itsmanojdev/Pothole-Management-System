<?php

use Illuminate\Support\Facades\Route;

// Common Auth Routes
Route::middleware('auth')->group(function () {
    Route::get('/user/profile', function () {
        return view('profile');
    })->name('profile');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/citizen.php';

?>
