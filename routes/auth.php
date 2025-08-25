<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Auth\LoginController;
use App\Http\Controllers\Web\Auth\ForgetPasswordController;
use App\Http\Controllers\Web\Auth\RegisterController;
use App\Http\Controllers\Web\Auth\LogoutController;
use App\Http\Controllers\Web\Auth\AdminSetupPasswordController;

// Authentication Routes with gym context
Route::prefix('gym/{siteSetting:slug}/auth')->name('auth.')->middleware(['guest', 'store.gym.context', 'share.site.setting'])->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login.index');
    Route::post('/login', [LoginController::class, 'login'])->name('login')->middleware('throttle:5,1');
    Route::get('/register', [RegisterController::class, 'index'])->name('register.index');
    Route::post('/register', [RegisterController::class, 'register'])->name('register')->middleware('throttle:5,1');


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

Route::prefix('auth')->middleware(['auth:web'])->group(function () {
    Route::prefix('logout')->controller(LogoutController::class)->group(function () {
        Route::post('/current',  'logoutFromCurrentSession')->name('auth.logout.current');
        Route::post('/all', 'logoutFromAllSessions')->name('auth.logout.all');
        Route::post('/others', 'logoutFromOtherSessions')->name('auth.logout.others');
    });
});