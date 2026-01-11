<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Public routes with rate limiting to prevent abuse
Route::middleware(['throttle:60,1'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/temperature', [DashboardController::class, 'temperature'])->name('temperature');
    Route::get('/humidity', [DashboardController::class, 'humidity'])->name('humidity');
    Route::get('/pressure', [DashboardController::class, 'pressure'])->name('pressure');
    Route::get('/brightness', [DashboardController::class, 'brightness'])->name('brightness');
    Route::get('/co2', [DashboardController::class, 'co2'])->name('co2');
    Route::get('/tvoc', [DashboardController::class, 'tvoc'])->name('tvoc');
});
