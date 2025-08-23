<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Auth\LoginController;
use App\Http\Controllers\Web\Auth\ForgetPasswordController;
use App\Http\Controllers\Web\Auth\RegisterController;
use App\Http\Controllers\Web\Auth\LogoutController;
use App\Http\Controllers\Web\Auth\AdminSetupPasswordController;

// Authentication Routes
Route::prefix('auth')->middleware(['guest', 'require.gym.context'])->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('auth.login.index');
    Route::post('/login', [LoginController::class, 'login'])->name('auth.login');
    Route::get('/register', [RegisterController::class, 'index'])->name('auth.register.index');
    Route::post('/register', [RegisterController::class, 'register'])->name('auth.register');

    Route::prefix('forget-password')->controller(ForgetPasswordController::class)->group(function () {
        Route::get('/reset', 'resetForm')->name('auth.forget-password.reset-form');
        Route::get('/', 'index')->name('auth.forget-password.index');
        Route::post('/send-code', 'sendCode')->name('auth.forget-password.send-code');
        Route::post('/reset', 'resetPassword')->name('auth.forget-password.reset');
    });

    Route::prefix('admin-setup-password')->controller(AdminSetupPasswordController::class)->group(function () {
        Route::get('/', 'showSetupForm')->name('auth.admin-setup-password');
        Route::post('/', 'setupPassword')->name('auth.admin-setup-password');
    });
});

Route::prefix('auth')->middleware(['auth:web'])->group(function () {
    Route::prefix('logout')->controller(LogoutController::class)->group(function () {
        Route::post('/current',  'logoutFromCurrentSession')->name('auth.logout.current');
        Route::post('/all', 'logoutFromAllSessions')->name('auth.logout.all');
        Route::post('/others', 'logoutFromOtherSessions')->name('auth.logout.others');
    });
});