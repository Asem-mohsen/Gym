<?php

use Illuminate\Support\Facades\Route;

// Authentication
use App\Http\Controllers\Web\Auth\LoginController;
use App\Http\Controllers\Web\Auth\ForgetPasswordController;
use App\Http\Controllers\Web\Auth\RegisterController;
use App\Http\Controllers\Web\Auth\LogoutController;

use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\BookingController;
use App\Http\Controllers\Web\ContactController;
use App\Http\Controllers\Web\MembershipController;
use App\Http\Controllers\Web\RolesController;
use App\Http\Controllers\Web\ServicesController;
use App\Http\Controllers\Web\TransactionController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\AboutController;
use App\Http\Controllers\Web\BranchController;
use App\Http\Controllers\Web\LockerController;
use App\Http\Controllers\Web\MachineController;
use App\Http\Controllers\Web\OfferController;
use App\Http\Controllers\Web\PaymentsController;
use App\Http\Controllers\Web\SiteSettingController;
use App\Http\Controllers\Web\StripePaymentController;
use App\Http\Controllers\Web\SubscriptionController;

// Web Routes

    // Public Routes
    Route::get('/gym', [HomeController::class, 'index'])->name('home.index');

    Route::prefix('about')->group(function () {
        Route::get('/about-us', [AboutController::class, 'aboutUs'])->name('about.about-us');
    });

    Route::prefix('contact')->group(function () {
        Route::get('/contact-us', [ContactController::class, 'contactUs'])->name('contact.contact-us');
    });

    Route::prefix('trainers')->group(function () {
        Route::get('/{user}/coach', [UserController::class, 'coachProfile'])->name('trainers.coach-profile');
    });

    Route::prefix('memberships')->group(function () {
        Route::get('/', [MembershipController::class, 'index'])->name('memberships.index');
        Route::get('/{membership}/membership', [MembershipController::class, 'show'])->name('memberships.show');
    });

    Route::prefix('services')->group(function () {
        Route::get('/services', [ServicesController::class, 'services'])->name('services.index');
    });

    Route::prefix('booking')->group(function () {
        Route::post('/membership/booking', [BookingController::class, 'bookMembership'])->name('booking.book-membership');
        Route::post('/coach/booking', [BookingController::class, 'bookCoach'])->name('booking.book-coach');
        Route::post('/service/booking', [BookingController::class, 'bookService'])->name('booking.book-service');
    });

    // Authentication Routes
    Route::prefix('auth')->middleware(['guest'])->group(function () {
        Route::get('/login', [LoginController::class, 'index'])->name('auth.login.index');
        Route::post('/login', [LoginController::class, 'login'])->name('auth.login');
        Route::post('/register', [RegisterController::class, 'register'])->name('auth.register');

        Route::prefix('forget-password')->group(function () {
            Route::post('/send-code', [ForgetPasswordController::class, 'sendCode'])->name('auth.forget-password.send-code');
            Route::post('/verify-code', [ForgetPasswordController::class, 'verifyCode'])->name('auth.forget-password.verify-code');
            Route::post('/reset', [ForgetPasswordController::class, 'resetPassword'])->name('auth.forget-password.reset');
        });
    });

    Route::prefix('auth')->middleware(['auth:web'])->group(function () {
        Route::prefix('logout')->group(function () {
            Route::post('/current', [LogoutController::class, 'logoutFromCurrentSession'])->name('auth.logout.current');
            Route::post('/all', [LogoutController::class, 'logoutFromAllSessions'])->name('auth.logout.all');
            Route::post('/others', [LogoutController::class, 'logoutFromOtherSessions'])->name('auth.logout.others');
        });

        Route::prefix('profile')->group(function () {
            Route::get('/', [UserController::class, 'profile'])->name('profile.index');
            Route::get('/edit', [UserController::class, 'edit'])->name('profile.edit');
            Route::put('/update', [UserController::class, 'update'])->name('profile.update');
            Route::delete('/delete', [UserController::class, 'destroy'])->name('profile.delete');
        });

        Route::prefix('booking')->group(function () {
            Route::post('/payment', [StripePaymentController::class, 'store'])->name('booking.payment');
        });
    });

    // Admin Routes
    Route::prefix('admin')->middleware(['auth:web', 'admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

        Route::resource('admins',AdminController::class);

        Route::resource('membership',MembershipController::class);

        Route::resource('machines',MachineController::class);

        Route::resource('roles',RolesController::class);

        Route::resource('users',UserController::class);

        Route::resource('services',ServicesController::class);

        Route::resource('offers',OfferController::class)->except('show');

        Route::prefix('offers')->controller(OfferController::class)->name('offers.')->group(function () {
            Route::get('/memberships',  'getMemberships')->name('memberships');
            Route::get('/services', 'getServices')->name('services');
           
        });
        
        Route::resource('subscriptions', SubscriptionController::class);
        Route::get('/get-offers', [SubscriptionController::class, 'getOffers']);

        Route::resource('branches',BranchController::class);

        Route::resource('payments',PaymentsController::class)->only(['index']);

        Route::resource('site-settings',SiteSettingController::class)->only(['edit' , 'update']);

        Route::resource('lockers',LockerController::class)->only(['index' , 'update']);
        Route::post('/lockers/{locker}/toggle', [LockerController::class, 'toggleLock'])->name('lockers.toggle');

        Route::prefix('transactions')->group(function () {
            Route::get('/', [TransactionController::class, 'index'])->name('admin.transactions.index');
        });

        Route::prefix('about')->group(function () {
            Route::get('/', [AboutController::class, 'index'])->name('admin.about.index');
        });
    });
