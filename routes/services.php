<?php

use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceMenuController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // Define specific routes before resource routes to avoid conflicts
    // Route::get('/menu', [ServiceMenuController::class, 'index'])->name('services.service-menu');

    // Resource routes - this will create routes like /services/{service} which could conflict
    // So we define the specific route above first
    Route::resource('services', ServiceController::class);
});

