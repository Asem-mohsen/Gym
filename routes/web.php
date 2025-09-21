<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\User\HomeController;
use App\Http\Controllers\Web\User\ContactController;
use App\Http\Controllers\Web\User\MembershipController;
use App\Http\Controllers\Web\User\ServicesController;
use App\Http\Controllers\Web\User\AboutController;
use App\Http\Controllers\Web\User\BlogController;
use App\Http\Controllers\Web\User\ClassesController;
use App\Http\Controllers\Web\User\GalleryController;
use App\Http\Controllers\Web\User\TeamController;
use App\Http\Controllers\Web\User\GymSelectionController;
use App\Http\Controllers\Web\User\InvitationController;
use App\Http\Controllers\Web\User\NotFoundController;
use App\Http\Controllers\Web\User\CheckinController;
use App\Http\Controllers\Web\User\BranchController;
use App\Http\Controllers\Web\User\CheckoutController;
use App\Http\Controllers\Web\User\StripePaymentController;
use App\Http\Controllers\Web\User\FawryPaymentController;
use App\Http\Controllers\Webhooks\StripeWebhookController;
use App\Http\Controllers\Webhooks\FawryWebhookController;

// Public Routes
Route::get('/', [GymSelectionController::class, 'index'])->name('gym.selection');

Route::prefix('gym/{siteSetting:slug}')->name('user.')->middleware(['store.gym.context', 'share.site.setting', 'check.gym.visibility'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/about-us', [AboutController::class, 'aboutUs'])->name('about-us');

    Route::prefix('classes')->name('classes.')->controller(ClassesController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{class}', 'show')->name('show');
    });

    Route::prefix('services')->name('services.')->controller(ServicesController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{service}', 'show')->name('show');
    });

    Route::prefix('branches')->name('branches.')->controller(BranchController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{branch}', 'show')->name('show');
    });

    Route::get('/trainers', [TeamController::class, 'index'])->name('team');

    Route::controller(GalleryController::class)->group(function () {
        Route::get('/gallery', 'index')->name('gallery');
        Route::get('/gallery/{id}', 'show')->name('gallery.show');
        Route::get('/branches/{branchId}/gallery', 'branchGalleries')->name('branch.gallery');
        Route::get('/branches/{branchId}/gallery/{galleryId}', 'branchGallery')->name('branch.gallery.show');
    });

    Route::controller(BlogController::class)->group(function () {
        Route::get('/blog', 'index')->name('blog');
        Route::get('/blog/{blogPost}', 'show')->name('blog.show');
    });

    Route::controller(ContactController::class)->group(function () {
        Route::get('/contact', 'index')->name('contact');
        Route::post('/contact', 'store')->name('contact.store');
    });

    Route::prefix('memberships')->controller(MembershipController::class)->group(function () {
        Route::get('/', 'index')->name('memberships.index');
        Route::get('/{membership}/membership', 'show')->name('memberships.show');
    });

    Route::post('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
    
    // Stripe Payment Routes
    Route::prefix('stripe')->controller(StripePaymentController::class)->name('payment.stripe.')->group(function () {
        Route::get('/return', 'return')->name('return');
        Route::get('/cancel', 'cancel')->name('cancel');
    });

    Route::prefix('fawry')->controller(FawryPaymentController::class)->name('payment.fawry.')->group(function () {
        Route::post('/process', 'processPayment')->name('process');
        Route::get('/return', 'handleReturn')->name('return');
        Route::get('/status', 'checkStatus')->name('status');
    });

    Route::middleware(['auth:web'])->group(function () {

        Route::prefix('invitations')->controller(InvitationController::class)->group(function () {
            Route::get('/', 'index')->name('invitations.index');
            Route::get('/create', 'create')->name('invitations.create');
            Route::post('/', 'store')->name('invitations.store');
            Route::get('/verify', 'verify')->name('invitations.verify');
            Route::get('/scan/{qrCode}', 'scanAndVerify')->name('invitations.scan');
            Route::post('/{invitation}/resend', 'resend')->name('invitations.resend');
        });
    
        // Check-in routes
        Route::prefix('checkin')->controller(CheckinController::class)->group(function () {
            Route::get('/self', 'showSelfCheckin')->name('checkin.self');
            Route::post('/self', 'processSelfCheckin')->name('checkin.self.process');
            Route::get('/personal-qr', 'showPersonalQr')->name('checkin.personal-qr');
            Route::get('/history', 'showCheckinHistory')->name('checkin.history');
            Route::get('/stats', 'showCheckinStats')->name('checkin.stats');
            Route::get('/staff-scanner', 'showStaffScanner')->name('checkin.staff-scanner');
            Route::post('/gate', 'processGateCheckin')->name('checkin.gate');
        });

        Route::prefix('blog/{blogPost}')->controller(BlogController::class)->group(function () {
            Route::post('/comments', 'storeComment')->name('blog.comments.store');
            Route::post('/comments/{comment}/reply', 'storeReply')->name('blog.comments.reply');
            Route::post('/comments/{comment}/like', 'toggleLike')->name('blog.comments.like');
            Route::post('/shares', 'share')->name('blog.shares.store');
        });

    });
});

Route::get('/paymob/return', [CheckoutController::class, 'return'])->name('paymob.return'); // front redirect

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe.webhook');
Route::post('/fawry/webhook', [FawryWebhookController::class, 'handle'])->name('fawry.webhook');
Route::post('/fawry/callback', [FawryWebhookController::class, 'statusCallback'])->name('fawry.callback');

Route::get('/404', [NotFoundController::class, 'index'])->name('404');

Route::fallback(function () {
    return redirect()->route('404');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/user.php';