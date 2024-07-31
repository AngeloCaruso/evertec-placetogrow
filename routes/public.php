<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Public\MicrositeController as PublicMicrositeController;
use Illuminate\Support\Facades\Route;

Route::get('/microsites', [PublicMicrositeController::class, 'index'])->name('public.microsite.index');
Route::get('/microsites/{microsite}', [PublicMicrositeController::class, 'show'])->name('public.microsite.show');

Route::get('/payments/{reference}', [PaymentController::class, 'show'])->name('payments.show');
Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
