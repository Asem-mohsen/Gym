<?php

use Illuminate\Support\Facades\Route;

// Authentication
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\Admin\LockerController;
use App\Http\Controllers\Web\Admin\StripePaymentController;


// Authenticated Users Routes
Route::prefix('auth')->middleware(['auth:web'])->group(function () {
    Route::prefix('profile')->controller(UserController::class)->group(function () {
        Route::get('/', 'profile')->name('profile.index');
        Route::get('/edit', 'edit')->name('profile.edit');
        Route::put('/update', 'update')->name('profile.update');
        Route::delete('/delete', 'destroy')->name('profile.delete');
    });

    Route::prefix('booking')->group(function () {
        Route::post('/payment', [StripePaymentController::class, 'store'])->name('booking.payment');
    });

    Route::prefix('lockers')->controller(LockerController::class)->middleware('auth')->group(function () {
        Route::post('{locker}/lock', 'lock');
        Route::post('{locker}/unlock', 'unlock');
    });
});
