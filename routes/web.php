<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\User\HomeController;
use App\Http\Controllers\Web\Admin\BookingController;
use App\Http\Controllers\Web\User\ContactController;
use App\Http\Controllers\Web\User\MembershipController;
use App\Http\Controllers\Web\User\ServicesController;
use App\Http\Controllers\Web\User\AboutController;
use App\Http\Controllers\Web\User\BlogController;
use App\Http\Controllers\Web\User\ClassesController;
use App\Http\Controllers\Web\User\GalleryController;
use App\Http\Controllers\Web\User\TeamController;
use App\Http\Controllers\Web\User\GymSelectionController;
use App\Http\Controllers\Web\User\NotFoundController;

// Public Routes
Route::get('/', [GymSelectionController::class, 'index'])->name('gym.selection');

Route::prefix('gym/{siteSetting:slug}')->name('user.')->middleware(['store.gym.context', 'share.site.setting'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/about-us', [AboutController::class, 'aboutUs'])->name('about-us');

    Route::get('/class-details', [ClassesController::class, 'classDetails'])->name('classes');
    Route::get('/classes', [ClassesController::class, 'index'])->name('classes.index');
    Route::get('/classes/{class}', [ClassesController::class, 'show'])->name('classes.show');

    Route::get('/services', [ServicesController::class, 'index'])->name('services');
    Route::get('/trainers', [TeamController::class, 'index'])->name('team');

    Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
    Route::get('/gallery/{id}', [GalleryController::class, 'show'])->name('gallery.show');

    Route::get('/branches/{branchId}/gallery', [GalleryController::class, 'branchGalleries'])->name('branch.gallery');
    Route::get('/branches/{branchId}/gallery/{galleryId}', [GalleryController::class, 'branchGallery'])->name('branch.gallery.show');

    Route::get('/blog', [BlogController::class, 'index'])->name('blog');
    Route::get('/blog/{blogPost}', [BlogController::class, 'show'])->name('blog.show');

    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

    Route::prefix('memberships')->group(function () {
        Route::get('/', [MembershipController::class, 'index'])->name('memberships.index');
        Route::get('/{membership}/membership', [MembershipController::class, 'show'])->name('memberships.show');
        Route::get('/success', [MembershipController::class, 'success'])->name('memberships.success');
    });

    Route::prefix('payments')->middleware(['auth:web'])->group(function () {
        Route::post('/create-intent', [App\Http\Controllers\Web\User\PaymentController::class, 'createPaymentIntent'])->name('payments.create-intent');
    });

});

Route::get('/404', [NotFoundController::class, 'index'])->name('404');

Route::fallback(function () {
    return redirect()->route('404');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/user.php';