<?php

use Illuminate\Support\Facades\Route;

// Authentication
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\ForgetPasswordController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\LogoutController;

use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\MembershipController;
use App\Http\Controllers\API\RolesController;
use App\Http\Controllers\API\ServicesController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AboutController;
use App\Http\Controllers\API\GalleryController;
use App\Http\Controllers\API\SiteSettingController;
use App\Http\Controllers\API\StripePaymentController;


// API Routes
Route::prefix('v1')->group(function(){

    // Auth
    Route::middleware(['preventAuth'])->group(function () {

        Route::post('/login'    , [LoginController::class , 'login']);
        Route::post('/register' , [RegisterController::class , 'register']);

        Route::controller(ForgetPasswordController::class)->group(function(){
            Route::post('/forget-password/send-code', 'sendCode');
            Route::post('/forget-password/verify-code', 'verifyCode');
            Route::post('/forget-password/reset','resetPassword');
        });

    });

    // User Authencticated
    Route::middleware('auth:sanctum')->group(function () {

        Route::prefix('profile')->group(function(){
            Route::controller(UserController::class)->group(function(){
                Route::get('/profile', 'profile');
                Route::get('/edit', 'edit');
                Route::put('/update', 'update');
                Route::delete('/delete', 'destroy');
            });
        });

        Route::prefix('logout')->controller(LogoutController::class)->group(function(){
            Route::post('/current', 'logoutFromCurrentSession');
            Route::post('/all',  'logoutFromAllSessions');
            Route::post('/others', 'logoutFromOtherSessions');
        });

        Route::prefix('booking')->group(function(){
            Route::controller(StripePaymentController::class)->group(function(){
                Route::post('/payment', 'store');
            });
        });

    });

    // Admin
    Route::middleware(['auth:sanctum' ,'admin'])->group(function () {

        Route::controller(DashboardController::class)->group(function(){
            Route::get('/', 'index')->name('index');
        });

        Route::prefix('admins')->group(function(){
            Route::controller(AdminController::class)->group(function(){
                Route::get('/', 'index');
                Route::get('/{user}/edit', 'edit');
                Route::get('/{user}/profile', 'profile');
                Route::get('/create', 'create');
                Route::post('/store', 'store');
                Route::put('/{user}/update', 'update');
                Route::delete('/{user}/delete', 'destroy');
            });
        });

        Route::prefix('memberships')->group(function(){
            Route::controller(MembershipController::class)->group(function(){
                Route::get('/{membership}/edit', 'edit');
                Route::post('/store', 'store');
                Route::put('/{membership}/update', 'update');
                Route::delete('/{membership}/delete', 'destroy');
            });
        });

        Route::prefix('roles')->group(function(){
            Route::controller(RolesController::class)->group(function(){
                Route::get('/', 'index');
                Route::get('/{role}/edit', 'edit');
                Route::post('/store', 'store');
                Route::put('/{role}/update', 'update');
                Route::delete('/{role}/delete', 'destroy');
            });
        });

        Route::prefix('bookings')->group(function(){
            Route::controller(BookingController::class)->group(function(){
                Route::get('/', 'index');
                Route::get('/{booking}/show', 'show');
            });
        });

        Route::prefix('services')->group(function(){
            Route::controller(ServicesController::class)->group(function(){
                Route::get('/', 'index');
                Route::get('/{service}/edit', 'edit');
                Route::post('/store', 'store');
                Route::put('/{service}/update', 'update');
                Route::delete('/{service}/delete', 'destroy');
            });
        });

        Route::prefix('users')->group(function(){
            Route::controller(UserController::class)->group(function(){
                Route::get('/', 'index');
                Route::get('/{user}/edit', 'editByAdmin');
                Route::post('/store', 'addUsers');
                Route::put('/{user}/update', 'update');
                Route::delete('/{user}/delete', 'destroy');
            });
        });

        Route::prefix('trainers')->group(function(){
            Route::controller(UserController::class)->group(function(){
                Route::get('/trainers', 'trainers');
                Route::get('/{user}/edit', 'edit');
                Route::post('/store', 'store');
                Route::put('/{user}/update', 'update');
                Route::delete('/{user}/delete', 'destroy');
            });
        });

        Route::prefix('transactions')->group(function(){
            Route::controller(TransactionController::class)->group(function(){
                Route::get('/', 'index');
            });
        });

        Route::prefix('bookings')->group(function(){
            Route::controller(BookingController::class)->group(function(){
                Route::get('/', 'index');
                Route::get('/{booking}/show', 'show');
            });
        });

        Route::prefix('contact')->group(function(){
            Route::controller(ContactController::class)->group(function(){
                Route::get('/', 'index');
            });
        });

        Route::prefix('site-settings')->group(function(){
            Route::controller(SiteSettingController::class)->group(function(){
                Route::get('/', 'index');
            });
        });

        Route::prefix('about')->group(function(){
            Route::controller(AboutController::class)->group(function(){
                Route::get('/', 'index');
            });
        });

        Route::prefix('galleries')->group(function(){
            Route::controller(GalleryController::class)->group(function(){
                Route::get('/site', 'getSiteGalleries');
                Route::get('/branch/{branchId}', 'getBranchGalleries');
                Route::get('/{id}', 'show');
                Route::post('/site', 'createSiteGallery');
                Route::post('/branch/{branchId}', 'createBranchGallery');
                Route::put('/{id}', 'update');
                Route::delete('/{id}', 'destroy');
                Route::delete('/{galleryId}/media/{mediaId}', 'removeMedia');
                Route::post('/reorder', 'reorder');
                Route::get('/stats', 'stats');
            });
        });

    });


    // User Public - Gym-specific routes
    Route::prefix('{gym:slug}')->group(function(){
        Route::controller(HomeController::class)->group(function(){
            Route::get('/', 'index')->name('index');
        });

        Route::prefix('trainers')->group(function(){
            Route::controller(UserController::class)->group(function(){
                Route::get('/{user}/coach', 'coachProfile');
            });
        });

        Route::prefix('memberships')->group(function(){
            Route::controller(MembershipController::class)->group(function(){
                Route::get('/', 'index');
                Route::get('/{membership}/membership', 'show');
            });
        });

        Route::prefix('contact')->group(function(){
            Route::controller(ContactController::class)->group(function(){
                Route::get('/', 'index');
                Route::post('/', 'sendMessage');
            });
        });

        Route::prefix('about')->group(function(){
            Route::controller(AboutController::class)->group(function(){
                Route::get('/about-us', 'aboutUs');
            });
        });

        Route::prefix('booking')->group(function(){
            Route::controller(BookingController::class)->group(function(){
                Route::post('/membership/booking', 'bookMembership');
                Route::post('/coach/booking', 'bookCoach');
                Route::post('/service/booking', 'bookService');
            });
        });

        Route::prefix('services')->group(function(){
            Route::controller(ServicesController::class)->group(function(){
                Route::get('/services', 'services');
            });
        });
    });

});
