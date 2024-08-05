<?php

use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\MicrositeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect('/login'));

Route::middleware(['middleware' => 'auth'])->group(function () {
    Route::get('locale/{locale}', [LocalizationController::class, 'changeLocale'])->name('locale');

    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('profile', 'profile')->name('profile');

    Route::resource('microsites', MicrositeController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
});


require __DIR__ . '/auth.php';
