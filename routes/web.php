<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Models\Order; 



Route::get('/checkout', [PaymentController::class, 'showPaymentForm']);
Route::post('/payment/process', [PaymentController::class, 'processPayment']);