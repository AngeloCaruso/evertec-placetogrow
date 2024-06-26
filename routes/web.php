<?php

use App\Http\Controllers\MicrositeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::resource('microsites', MicrositeController::class);
Route::resource('roles', RoleController::class);
Route::resource('users', UserController::class);

require __DIR__ . '/auth.php';
