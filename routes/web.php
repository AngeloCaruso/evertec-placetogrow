<?php

use App\Http\Controllers\MicrositeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect('/login'));

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::resource('microsites', MicrositeController::class)->only(['index', 'create', 'edit', 'destroy']);
Route::resource('roles', RoleController::class)->only(['index', 'create', 'edit', 'destroy']);
Route::resource('users', UserController::class)->only(['index', 'create', 'edit', 'destroy']);

require __DIR__ . '/auth.php';
