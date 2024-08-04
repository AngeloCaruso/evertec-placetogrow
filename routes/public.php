<?php

use App\Http\Controllers\Public\MicrositeController as PublicMicrositeController;
use App\Http\Controllers\Public\PaymentController as PublicPaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/microsites', [PublicMicrositeController::class, 'index'])->name('public.microsite.index');
Route::get('/microsites/{microsite}', [PublicMicrositeController::class, 'show'])->name('public.microsite.show');

Route::get('/payments/{reference}', [PublicPaymentController::class, 'show'])->name('public.payments.show');
Route::post('/payments', [PublicPaymentController::class, 'store'])->name('public.payments.store');
