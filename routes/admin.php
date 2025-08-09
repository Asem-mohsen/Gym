<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Admin\DashboardController;
use App\Http\Controllers\Web\Admin\AdminController;
use App\Http\Controllers\Web\Admin\BlogPostController;
use App\Http\Controllers\Web\Admin\MembershipController;
use App\Http\Controllers\Web\Admin\RolesController;
use App\Http\Controllers\Web\Admin\ServicesController;
use App\Http\Controllers\Web\Admin\TransactionController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\Admin\BranchController;
use App\Http\Controllers\Web\Admin\ClassController;
use App\Http\Controllers\Web\Admin\LockerController;
use App\Http\Controllers\Web\Admin\MachineController;
use App\Http\Controllers\Web\Admin\OfferController;
use App\Http\Controllers\Web\Admin\PaymentsController;
use App\Http\Controllers\Web\Admin\SiteSettingController;
use App\Http\Controllers\Web\Admin\SubscriptionController;
use App\Http\Controllers\Web\Admin\GalleryController;
use App\Http\Controllers\Web\Admin\FeatureController;

// Admin Routes
Route::prefix('admin')->middleware(['auth:web', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::resource('admins',AdminController::class);

    Route::resource('membership',MembershipController::class);

    Route::resource('features',FeatureController::class);

    Route::resource('machines',MachineController::class);

    Route::resource('roles',RolesController::class);

    Route::resource('blog-posts',BlogPostController::class);

    Route::resource('users',UserController::class);
    Route::get('trainers',[UserController::class,'trainers'])->name('trainers');

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

    Route::get('site-settings/edit', [SiteSettingController::class, 'edit'])->name('site-settings.edit');
    Route::put('site-settings/update', [SiteSettingController::class, 'update'])->name('site-settings.update');

    Route::resource('classes', ClassController::class);

    // Gallery routes - site setting comes from authenticated user
    Route::resource('galleries', GalleryController::class)->except(['show']);
    Route::delete('galleries/{galleryId}/media/{mediaId}', [GalleryController::class, 'removeMedia'])->name('galleries.remove-media');

    Route::controller(LockerController::class)->middleware(['auth', 'can:adminUnlock,App\Models\Locker'])->group(function () {
        Route::get('lockers', 'index')->name('lockers.index');
        Route::post('lockers/{locker}/lock', 'lock');
        Route::post('lockers/{locker}/unlock', 'unlock');
        Route::post('lockers/{locker}/recovery-token', 'generateRecoveryToken');
        Route::post('lockers/{locker}/unlock-with-token', 'unlockWithToken');
    });

    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('admin.transactions.index');
    });
});