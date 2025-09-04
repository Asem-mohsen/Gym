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
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AboutController;
use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\BlogPostShareController;
use App\Http\Controllers\API\ClassesController;
use App\Http\Controllers\API\GalleryController;
use App\Http\Controllers\API\SiteSettingController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\GymContextController;
use App\Http\Controllers\API\CheckinController;
use App\Http\Controllers\API\NotificationController;

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

    // Gym Context Management (available for both guests and authenticated users)
    Route::controller(GymContextController::class)->group(function(){
        Route::get('/gym-context', 'getCurrentContext');
        Route::post('/gym-context', 'updateContext');
        Route::delete('/gym-context', 'clearContext');
        Route::post('/gym-context/validate', 'validateContext');
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

        // Notification routes for authenticated users
        Route::prefix('notifications')->controller(NotificationController::class)->group(function(){
            Route::get('/', 'index');
            Route::get('/recent', 'recent');
            Route::get('/unread-count', 'unreadCount');
            Route::post('/{id}/mark-read', 'markAsRead');
            Route::post('/mark-all-read', 'markAllAsRead');
            Route::delete('/{id}', 'destroy');
        });

        // Payment routes for authenticated users
        Route::prefix('{gym:slug}')->group(function(){
            Route::prefix('payments')->group(function(){
                Route::controller(PaymentController::class)->group(function(){
                    Route::post('/create-intent', 'createPaymentIntent')->name('payments.create-intent');
                    Route::post('/confirm', 'confirmPayment')->name('payments.confirm');
                });
            });

            Route::prefix('mobile')->group(function(){
                Route::controller(PaymentController::class)->group(function(){
                    Route::post('/enroll-membership', 'mobileEnrollMembership')->name('mobile.enroll-membership');
                    Route::get('/payment-status/{paymentIntentId}', 'getPaymentStatus')->name('mobile.payment-status');
                });
            });
        });
    });

    // User Public - Gym-specific routes
    Route::prefix('{gym:slug}')->middleware(['store.gym.context', 'share.site.setting'])->group(function(){
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
            
            Route::prefix('{blogPost}/comments')->group(function(){
                Route::controller(CommentController::class)->group(function(){
                    Route::get('/', 'getComments');
                    Route::post('/', 'store');
                    Route::put('/{comment}', 'update');
                    Route::delete('/{comment}', 'destroy');
                    Route::post('/{comment}/reply', 'reply');
                    Route::post('/{comment}/like', 'toggleLike');
                });
            });
            
            Route::prefix('{blogPost}/shares')->group(function(){
                Route::controller(BlogPostShareController::class)->group(function(){
                    Route::post('/', 'share');
                    Route::get('/statistics', 'getShareStatistics');
                    Route::get('/urls', 'getSharingUrls');
                });
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

        // Check-in routes
        Route::prefix('checkin')->group(function(){
            Route::controller(CheckinController::class)->group(function(){
                Route::post('/self', 'selfCheckin');
                Route::post('/gate', 'gateCheckin');
                Route::get('/personal-qr', 'getPersonalQr');
                Route::get('/stats', 'getCheckinStats');
                Route::get('/history', 'getUserCheckinHistory');
                Route::post('/validate-token', 'validateQrToken');
            });
        });

    });
});
