<?php

use Illuminate\Support\Facades\Route;

// Authentication
use App\Http\Controllers\Web\User\UserController;
use App\Http\Controllers\Web\User\NotificationController;
use App\Http\Controllers\Web\Admin\LockerController;

// Authenticated Users Routes
Route::prefix('auth')->middleware(['auth:web'])->group(function () {

    Route::prefix('gym/{siteSetting:slug}')->middleware(['store.gym.context', 'share.site.setting', 'check.gym.visibility'])->group(function () {

        Route::prefix('/profile')->controller(UserController::class)->group(function () {
            Route::get('/', 'index')->name('profile.index');
            Route::get('/edit', 'edit')->name('profile.edit');
            Route::put('/update', 'update')->name('profile.update');
            Route::delete('/delete', 'delete')->name('profile.delete');
        });

        Route::prefix('/notifications')->controller(NotificationController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/recent', 'recent');
            Route::get('/unread-count', 'unreadCount');
            Route::post('/{id}/mark-read', 'markAsRead');
            Route::post('/mark-all-read', 'markAllAsRead');
            Route::delete('/{id}', 'destroy');
        });

    });

    Route::prefix('lockers')->controller(LockerController::class)->middleware('auth')->group(function () {
        Route::post('{locker}/lock', 'lock');
        Route::post('{locker}/unlock', 'unlock');
    });
});
