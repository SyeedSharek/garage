<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderCreateController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // Order creation routes (moved to separate controller)
    Route::get('orders/create', [OrderCreateController::class, 'create'])->name('orders.create');
    Route::get('orders/create/details', [OrderCreateController::class, 'createDetails'])->name('orders.create-details');
    Route::get('orders/create/prices', [OrderCreateController::class, 'createPrices'])->name('orders.create-prices');
    Route::get('orders/create/preview', [OrderCreateController::class, 'createPreview'])->name('orders.create-preview');
    Route::post('orders', [OrderCreateController::class, 'store'])->name('orders.store');

    // Payment selection routes
    Route::get('orders/{order}/payment', [OrderController::class, 'payment'])->name('orders.payment');
    Route::get('invoices/{invoice}/payment', [OrderController::class, 'paymentByInvoice'])->name('invoices.payment');
    Route::post('orders/{order}/payment/process', [OrderController::class, 'processPayment'])->name('orders.payment.process');
    Route::post('invoices/{invoice}/payment/process', [OrderController::class, 'processPaymentByInvoice'])->name('invoices.payment.process');

    // Other order routes
    Route::resource('orders', OrderController::class)->except(['create', 'store']);
});

