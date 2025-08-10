<?php

use Illuminate\Support\Facades\Route;

// Authentication
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\ForgetPasswordController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\LogoutController;

use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\MembershipController;
use App\Http\Controllers\API\ServicesController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AboutController;
use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\ClassesController;
use App\Http\Controllers\API\GalleryController;
use App\Http\Controllers\API\SiteSettingController;
use App\Http\Controllers\API\StripePaymentController;


// API Routes
Route::prefix('v1')->group(function(){

    // Guest
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

    });

    // User Public - Gym-specific routes
    Route::prefix('{gym:slug}')->group(function(){
        Route::controller(HomeController::class)->group(function(){
            Route::get('/', 'index')->name('index');
        });

        Route::prefix('trainers')->group(function(){
            Route::controller(UserController::class)->group(function(){
                Route::get('/', 'trainers');
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

        Route::prefix('users/{user}')->group(function(){
            Route::controller(UserController::class)->group(function(){
                Route::get('/profile', 'profile');
                Route::get('/edit', 'edit');
                Route::put('/update', 'update');
                Route::delete('/delete', 'destroy');
            });
        });

        Route::prefix('classes')->group(function(){
            Route::controller(ClassesController::class)->group(function(){
                Route::get('/', 'index');
                Route::get('/{class}', 'show');
            });
        });

        Route::prefix('blog')->group(function(){
            Route::controller(BlogController::class)->group(function(){
                Route::get('/', 'index');
                Route::get('/{blogPost}', 'show');
            });
        });

        Route::prefix('galleries')->group(function(){
            Route::controller(GalleryController::class)->group(function(){
                Route::get('/', 'index');
                Route::get('/branch/{branch}', 'getBranchGalleries');
                Route::get('/{gallery}', 'show');
            });
        });

        Route::prefix('site-settings')->group(function(){
            Route::controller(SiteSettingController::class)->group(function(){
                Route::get('/', 'index');
            });
        });

    });
});
