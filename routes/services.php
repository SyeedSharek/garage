<?php

use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceMenuController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // Define specific routes before resource routes to avoid conflicts
    Route::get('services/service-menu', [ServiceMenuController::class, 'index'])
        ->name('services.service-menu')
        ->where('service-menu', 'service-menu');
    
    // Resource routes
    Route::resource('services', ServiceController::class);
});

