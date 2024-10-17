<?php

declare(strict_types=1);

use App\Http\Controllers\AccessControlListController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataImportController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\MicrositeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Localization;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect('/microsites'));
Route::get('locale/{locale}', [LocalizationController::class, 'changeLocale'])->name('locale');

Route::middleware(['auth', 'verified', Localization::class])
    ->prefix('admin')
    ->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::view('profile', 'profile')->name('profile');

        Route::resource('microsites', MicrositeController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('users', UserController::class);
        Route::resource('acl', AccessControlListController::class)->except(['show']);
        Route::resource('payments', PaymentController::class)->only(['index', 'show']);
        Route::resource('subscriptions', SubscriptionController::class)->only(['index', 'show']);
        Route::resource('data-imports', DataImportController::class)->only(['index', 'show', 'create']);
    });

require __DIR__ . '/auth.php';
require __DIR__ . '/public.php';
