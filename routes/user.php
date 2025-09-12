<?php

use Illuminate\Support\Facades\Route;

// Authentication
use App\Http\Controllers\Web\User\UserController;
use App\Http\Controllers\Web\Admin\LockerController;
use App\Http\Controllers\Web\Admin\StripePaymentController;


// Authenticated Users Routes
Route::prefix('auth')->middleware(['auth:web'])->group(function () {

    Route::prefix('gym/{siteSetting:slug}')->middleware(['store.gym.context', 'share.site.setting', 'check.gym.visibility'])->group(function () {

        Route::prefix('/profile')->controller(UserController::class)->group(function () {
            Route::get('/', 'index')->name('profile.index');
            Route::get('/edit', 'edit')->name('profile.edit');
            Route::put('/update', 'update')->name('profile.update');
            Route::delete('/delete', 'delete')->name('profile.delete');
        });

    });

    Route::prefix('lockers')->controller(LockerController::class)->middleware('auth')->group(function () {
        Route::post('{locker}/lock', 'lock');
        Route::post('{locker}/unlock', 'unlock');
    });
});
