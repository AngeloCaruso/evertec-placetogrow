<?php

declare(strict_types=1);

use App\Http\Controllers\AccessControlListController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\MicrositeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Localization;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect('/microsites'));

Route::middleware(['middleware' => 'auth', Localization::class])
    ->prefix('admin')
    ->group(function () {
        Route::get('locale/{locale}', [LocalizationController::class, 'changeLocale'])->name('locale');

        Route::view('dashboard', 'dashboard')->name('dashboard');
        Route::view('profile', 'profile')->name('profile');

        Route::resource('microsites', MicrositeController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('users', UserController::class);
        Route::resource('acl', AccessControlListController::class)->except(['show']);
        Route::resource('payments', PaymentController::class)->only(['index', 'show']);
    });

require __DIR__ . '/auth.php';
require __DIR__ . '/public.php';
