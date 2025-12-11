<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/temperature', [DashboardController::class, 'temperature'])->name('temperature');
Route::get('/humidity', [DashboardController::class, 'humidity'])->name('humidity');
Route::get('/pressure', [DashboardController::class, 'pressure'])->name('pressure');
