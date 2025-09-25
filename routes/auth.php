<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Auth\LoginController;
use App\Http\Controllers\Web\Auth\ForgetPasswordController;
use App\Http\Controllers\Web\Auth\RegisterController;
use App\Http\Controllers\Web\Auth\LogoutController;
use App\Http\Controllers\Web\Auth\AdminSetupPasswordController;

// Authentication Routes with gym context
Route::prefix('gym/{siteSetting:slug}/auth')->name('auth.')->group(function () {

    Route::middleware(['auth:web'])->prefix('/logout')->name('logout.')->controller(LogoutController::class)->group(function () {
        Route::post('/current',  'logoutFromCurrentSession')->name('current');
        Route::post('/all', 'logoutFromAllSessions')->name('all');
        Route::post('/others', 'logoutFromOtherSessions')->name('others');
    });

    Route::middleware(['guest', 'share.site.setting'])->group(function () {

        Route::prefix('login')->controller(LoginController::class)->group(function () {
            Route::get('/', 'index')->name('login.index');
            Route::post('/', 'login')->name('login')->middleware('throttle:5,1');
        });
        
        Route::prefix('register')->controller(RegisterController::class)->group(function () {
            Route::get('/', 'index')->name('register.index');
            Route::post('/', 'register')->name('register')->middleware('throttle:5,1');
        });
    
        Route::prefix('forget-password')->controller(ForgetPasswordController::class)->group(function () {
            Route::get('/reset', 'resetForm')->name('forget-password.reset-form');
            Route::get('/', 'index')->name('forget-password.index');
            Route::post('/send-code', 'sendCode')->name('forget-password.send-code')->middleware('throttle:5,1');
            Route::post('/reset', 'resetPassword')->name('forget-password.reset')->middleware('throttle:5,1');
        });
    
        Route::prefix('admin-setup-password')->controller(AdminSetupPasswordController::class)->group(function () {
            Route::get('/', 'showSetupForm')->name('admin-setup-password');
            Route::post('/', 'setupPassword')->name('admin-setup-password')->middleware('throttle:5,1');
        });

    });

});