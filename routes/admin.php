<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Admin\DashboardController;
use App\Http\Controllers\Web\Admin\AdminController;
use App\Http\Controllers\Web\Admin\BlogPostController;
use App\Http\Controllers\Web\Admin\MembershipController;
use App\Http\Controllers\Web\Admin\RolesController;
use App\Http\Controllers\Web\Admin\ServicesController;
use App\Http\Controllers\Web\Admin\TransactionController;
use App\Http\Controllers\Web\Admin\UserController;
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
use App\Http\Controllers\Web\Admin\ScoreDashboardController;
use App\Http\Controllers\Web\Admin\ReviewRequestController;
use App\Http\Controllers\Web\Admin\ResourcesController;
use App\Http\Controllers\Web\Admin\GymDeactivationController;
use App\Http\Controllers\Web\Admin\CashPaymentController;

// Admin Routes
Route::prefix('admin')->middleware(['auth:web', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::resource('admins',AdminController::class);
    Route::post('admins/{admin}/resend-onboarding-email', [AdminController::class, 'resendOnboardingEmail'])->name('admins.resend-onboarding-email');

    Route::resource('membership',MembershipController::class);

    Route::resource('features',FeatureController::class);

    Route::resource('machines',MachineController::class);

    Route::resource('roles',RolesController::class);

    Route::resource('blog-posts',BlogPostController::class);

    Route::resource('users',UserController::class);
    Route::get('trainers',[UserController::class,'trainers'])->name('trainers');
    Route::post('users/{user}/resend-onboarding-email', [UserController::class, 'resendOnboardingEmail'])->name('users.resend-onboarding-email');

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

    // Cash Payments Management Routes
    Route::prefix('cash-payments')->controller(CashPaymentController::class)->name('admin.cash-payments.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/mark-collected', 'markAsCollected')->name('mark-collected');
        Route::post('/mark-pending', 'markAsPending')->name('mark-pending');
        Route::get('/export', 'export')->name('export');
        Route::get('/statistics', 'getStatistics')->name('statistics');
    });

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

    Route::prefix('score-management')->group(function () {
        Route::get('/', [ScoreDashboardController::class, 'index'])->name('admin.score-dashboard');
        Route::get('/documents', [ScoreDashboardController::class, 'documents'])->name('admin.documents.index');
        Route::get('/documents/{document}/download', [ScoreDashboardController::class, 'downloadDocument'])->name('admin.documents.download');
        
        Route::resource('branch-scores', ScoreDashboardController::class)->except(['index']);
        
        Route::resource('review-requests', ReviewRequestController::class);
    });

    Route::prefix('resources')->group(function () {
        Route::get('/', [ResourcesController::class, 'index'])->name('admin.resources');
        Route::get('/{document}/download', [ResourcesController::class, 'download'])->name('admin.resources.download');
        Route::get('/{document}/view', [ResourcesController::class, 'view'])->name('admin.resources.view');
    });

    // Gym Deactivation Routes
    Route::prefix('deactivation')->controller(GymDeactivationController::class)->group(function () {
        Route::get('/', 'index')->name('admin.deactivation.index');
        Route::post('/gym/{siteSetting}/deactivate', 'deactivateGym')->name('admin.gym.deactivate');
        Route::post('/branch/{branch}/deactivate', 'deactivateBranch')->name('admin.branch.deactivate');
        Route::get('/gym/preview', 'getGymDataPreview')->name('admin.gym.preview');
        Route::get('/branch/{branch}/preview', 'getBranchDataPreview')->name('admin.branch.preview');
    });
});