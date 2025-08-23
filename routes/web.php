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

// Public Routes
Route::get('/', [GymSelectionController::class, 'index'])->name('gym.selection');

Route::prefix('gym/{siteSetting:slug}')->name('user.')->middleware(['store.gym.context', 'share.site.setting'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/about-us', [AboutController::class, 'aboutUs'])->name('about-us');

    Route::prefix('classes')->name('classes.')->controller(ClassesController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{class}', 'show')->name('show');
        Route::post('/{class}/book', 'book')->name('book')->middleware(['auth:web']);
    });

    Route::prefix('services')->name('services.')->controller(ServicesController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{service}', 'show')->name('show');
        Route::post('/{service}/book', 'book')->name('book')->middleware(['auth:web']);
    });

    Route::get('/trainers', [TeamController::class, 'index'])->name('team');

    Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
    Route::get('/gallery/{id}', [GalleryController::class, 'show'])->name('gallery.show');

    Route::get('/branches/{branchId}/gallery', [GalleryController::class, 'branchGalleries'])->name('branch.gallery');
    Route::get('/branches/{branchId}/gallery/{galleryId}', [GalleryController::class, 'branchGallery'])->name('branch.gallery.show');

    Route::get('/blog', [BlogController::class, 'index'])->name('blog');
    Route::get('/blog/{blogPost}', [BlogController::class, 'show'])->name('blog.show');

    Route::prefix('blog/{blogPost}')->group(function () {
        Route::post('/comments', [BlogController::class, 'storeComment'])->name('blog.comments.store');
        Route::post('/comments/{comment}/reply', [BlogController::class, 'storeReply'])->name('blog.comments.reply');
        Route::post('/comments/{comment}/like', [BlogController::class, 'toggleLike'])->name('blog.comments.like');
        Route::post('/shares', [BlogController::class, 'share'])->name('blog.shares.store');
    });

    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

    Route::prefix('memberships')->group(function () {
        Route::get('/', [MembershipController::class, 'index'])->name('memberships.index');
        Route::get('/{membership}/membership', [MembershipController::class, 'show'])->name('memberships.show');
    });

    Route::prefix('payments')->middleware(['auth:web'])->group(function () {
        Route::post('/create-intent', [App\Http\Controllers\Web\User\PaymentController::class, 'createPaymentIntent'])->name('payments.create-intent');
        Route::post('/paymob/initialize', [App\Http\Controllers\Web\User\PaymobPaymentController::class, 'initializePayment'])->name('payments.paymob.initialize');
        Route::post('/paymob/process-with-branch', [App\Http\Controllers\Web\User\PaymobPaymentController::class, 'processPaymentWithBranch'])->name('payments.paymob.process-with-branch');
        Route::get('/create/{bookingId}', [App\Http\Controllers\Web\User\PaymentController::class, 'createPayment'])->name('payment.create');
    });

    Route::prefix('invitations')->middleware(['auth:web'])->controller(InvitationController::class)->group(function () {
        Route::get('/', 'index')->name('invitations.index');
        Route::get('/create', 'create')->name('invitations.create');
        Route::post('/', 'store')->name('invitations.store');
        Route::get('/verify', 'verify')->name('invitations.verify');
        Route::get('/scan/{qrCode}', 'scanAndVerify')->name('invitations.scan');
        Route::post('/{invitation}/resend', 'resend')->name('invitations.resend');
    });

});

// Paymob callback routes (no auth required, no site setting context)
Route::post('/paymob/callback', [App\Http\Controllers\Web\User\PaymobPaymentController::class, 'handleCallback'])->name('payments.paymob.callback');
Route::get('/paymob/callback', [App\Http\Controllers\Web\User\PaymobPaymentController::class, 'handleCallback'])->name('payments.paymob.callback.get');

Route::get('/404', [NotFoundController::class, 'index'])->name('404');

Route::fallback(function () {
    return redirect()->route('404');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/user.php';