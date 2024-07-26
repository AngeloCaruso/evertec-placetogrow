<?php

use App\Http\Controllers\Public\MicrositeController as PublicMicrositeController;
use Illuminate\Support\Facades\Route;

Route::get('/microsites', [PublicMicrositeController::class, 'index']);