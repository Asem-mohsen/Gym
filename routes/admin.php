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
use App\Http\Controllers\Web\Admin\InvitationController;
use App\Http\Controllers\Web\Admin\TrainerController;
use App\Http\Controllers\Web\Admin\StaffController;
use App\Http\Controllers\Web\Admin\RolePermissionController;
use App\Http\Controllers\Web\Admin\ContactController;

// Admin Routes
Route::prefix('admin')->middleware(['auth:web', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    // User Management Routes
    Route::middleware(['permission:view_users'])->group(function () {
        Route::resource('admins', AdminController::class);
        Route::post('admins/{admin}/resend-onboarding-email', [AdminController::class, 'resendOnboardingEmail'])->name('admins.resend-onboarding-email');
        
        Route::resource('users', UserController::class);
        Route::post('users/{user}/resend-onboarding-email', [UserController::class, 'resendOnboardingEmail'])->name('users.resend-onboarding-email');
        
        Route::resource('invitations', InvitationController::class);
    });

    // Trainer Management Routes
    Route::middleware(['permission:view_trainers'])->group(function () {
        Route::resource('trainers', TrainerController::class);
        Route::post('trainers/{trainer}/resend-onboarding-email', [TrainerController::class, 'resendOnboardingEmail'])->name('trainers.resend-onboarding-email');
    });

    // Staff Management Routes
    Route::middleware(['permission:view_staff'])->group(function () {
        Route::resource('staff', StaffController::class);
        Route::post('staff/{staff}/resend-onboarding-email', [StaffController::class, 'resendOnboardingEmail'])->name('staff.resend-onboarding-email');
    });

    // Role and Permission Management Routes
    Route::middleware(['permission:manage_roles'])->group(function () {
        Route::get('roles', [RolePermissionController::class, 'index'])->name('roles.index');
        Route::get('roles/{role}', [RolePermissionController::class, 'show'])->name('roles.show');
        Route::put('roles/{role}/permissions', [RolePermissionController::class, 'updateRolePermissions'])->name('roles.update-permissions');
        Route::post('roles', [RolePermissionController::class, 'createRole'])->name('roles.create');
        Route::delete('roles/{role}', [RolePermissionController::class, 'deleteRole'])->name('roles.delete');
        
        Route::get('users/{user}/permissions', [RolePermissionController::class, 'userPermissions'])->name('users.permissions');
        Route::put('users/{user}/roles', [RolePermissionController::class, 'updateUserRoles'])->name('users.update-roles');
        Route::put('users/{user}/permissions', [RolePermissionController::class, 'updateUserPermissions'])->name('users.update-permissions');
        
        Route::get('permissions', [RolePermissionController::class, 'getPermissions'])->name('permissions.get');
        Route::get('roles/{role}/users', [RolePermissionController::class, 'getUsersByRole'])->name('roles.users');
    });

    // Membership Management Routes
    Route::middleware(['permission:view_memberships'])->group(function () {
        Route::resource('membership', MembershipController::class);
    });

    // Features Management Routes
    Route::middleware(['permission:view_features'])->group(function () {
        Route::resource('features', FeatureController::class);
    });

    // Services Management Routes
    Route::middleware(['permission:view_services'])->group(function () {
        Route::resource('services', ServicesController::class);
    });

    // Classes Management Routes
    Route::middleware(['permission:view_classes'])->group(function () {
        Route::resource('machines', MachineController::class);
    });

    // Roles Management Routes
    Route::middleware(['permission:manage_roles'])->group(function () {
        Route::resource('roles', RolesController::class);
    });

    // Blog Posts Management Routes
    Route::middleware(['permission:view_blog_posts'])->group(function () {
        Route::resource('blog-posts', BlogPostController::class);
    });

    // Financial Management Routes
    Route::middleware(['permission:view_financials'])->group(function () {
        Route::resource('offers', OfferController::class)->except('show');
        
        Route::prefix('offers')->controller(OfferController::class)->name('offers.')->group(function () {
            Route::get('/memberships', 'getMemberships')->name('memberships');
            Route::get('/services', 'getServices')->name('services');
        });
        
        Route::resource('payments', PaymentsController::class)->only(['index']);
        
        // Cash Payments Management Routes
        Route::prefix('cash-payments')->controller(CashPaymentController::class)->name('admin.cash-payments.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/mark-collected', 'markAsCollected')->name('mark-collected');
            Route::post('/mark-pending', 'markAsPending')->name('mark-pending');
            Route::get('/export', 'export')->name('export');
            Route::get('/statistics', 'getStatistics')->name('statistics');
        });
    });

            
    Route::middleware(['permission:view_subscriptions'])->group(function () {
        Route::resource('subscriptions', SubscriptionController::class);
        Route::get('/get-offers', [SubscriptionController::class, 'getOffers']);
    });

    // Site Settings Routes
    Route::middleware(['permission:view_site_settings'])->group(function () {
        Route::get('site-settings/edit', [SiteSettingController::class, 'edit'])->name('site-settings.edit');
        Route::put('site-settings/update', [SiteSettingController::class, 'update'])->name('site-settings.update');
    });

    // Branches Management Routes
    Route::middleware(['permission:view_branches'])->group(function () {
        Route::resource('branches', BranchController::class);
    });

    // Classes Management Routes
    Route::middleware(['permission:view_classes'])->group(function () {
        Route::resource('classes', ClassController::class);
    });

    // Gallery Management Routes
    Route::middleware(['permission:view_gallery'])->group(function () {
        Route::resource('galleries', GalleryController::class)->except(['show']);
        Route::delete('galleries/{galleryId}/media/{mediaId}', [GalleryController::class, 'removeMedia'])->name('galleries.remove-media');
    });

    // Lockers Management Routes
    Route::middleware(['permission:view_lockers'])->group(function () {
        Route::controller(LockerController::class)->middleware(['auth', 'can:adminUnlock,App\Models\Locker'])->group(function () {
            Route::get('lockers', 'index')->name('lockers.index');
            Route::post('lockers/{locker}/lock', 'lock');
            Route::post('lockers/{locker}/unlock', 'unlock');
            Route::post('lockers/{locker}/recovery-token', 'generateRecoveryToken');
            Route::post('lockers/{locker}/unlock-with-token', 'unlockWithToken');
        });
    });

    // Transactions Routes
    Route::middleware(['permission:view_payments'])->group(function () {
        Route::prefix('transactions')->group(function () {
            Route::get('/', [TransactionController::class, 'index'])->name('admin.transactions.index');
        });
    });

    // Score Management Routes
    Route::middleware(['permission:view_scores'])->group(function () {
        Route::prefix('score-management')->group(function () {
            Route::get('/', [ScoreDashboardController::class, 'index'])->name('admin.score-dashboard');
            Route::get('/documents', [ScoreDashboardController::class, 'documents'])->name('admin.documents.index');
            Route::get('/documents/{document}/download', [ScoreDashboardController::class, 'downloadDocument'])->name('admin.documents.download');
            
            Route::resource('branch-scores', ScoreDashboardController::class)->except(['index']);
        });
    });

    // Review Requests Routes
    Route::middleware(['permission:view_reviews_requests'])->group(function () {
        Route::prefix('score-management')->group(function () {
            Route::resource('review-requests', ReviewRequestController::class);
        });
    });

    // Resources Routes
    Route::middleware(['permission:view_resources'])->group(function () {
        Route::prefix('resources')->group(function () {
            Route::get('/', [ResourcesController::class, 'index'])->name('admin.resources');
            Route::get('/{document}/download', [ResourcesController::class, 'download'])->name('admin.resources.download');
            Route::get('/{document}/view', [ResourcesController::class, 'view'])->name('admin.resources.view');
        });
    });

    // Contact Management Routes
    Route::middleware(['permission:view_contacts'])->group(function () {
        Route::prefix('contacts')->controller(ContactController::class)->name('admin.contacts.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/{id}/mark-answered', 'markAsAnswered')->name('mark-answered');
        });
    });

    // Gym Deactivation Routes (Admin only)
    Route::middleware(['permission:manage_site_settings'])->group(function () {
        Route::prefix('deactivation')->controller(GymDeactivationController::class)->group(function () {
            Route::get('/', 'index')->name('admin.deactivation.index');
            Route::post('/gym/{siteSetting}/deactivate', 'deactivateGym')->name('admin.gym.deactivate');
            Route::post('/branch/{branch}/deactivate', 'deactivateBranch')->name('admin.branch.deactivate');
            Route::get('/gym/preview', 'getGymDataPreview')->name('admin.gym.preview');
            Route::get('/branch/{branch}/preview', 'getBranchDataPreview')->name('admin.branch.preview');
        });
    });
});