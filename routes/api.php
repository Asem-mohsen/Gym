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
use App\Http\Controllers\API\CheckinController;
use App\Http\Controllers\API\GymSelectionController;
use App\Http\Controllers\API\InvitationController;
use App\Http\Controllers\API\NotificationController;

// API Routes
Route::prefix('v1')->group(function(){

    // User Authencticated
    Route::middleware('auth:sanctum')->group(function () {

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
        Route::prefix('{gym:slug}')->middleware(['store.gym.context', 'share.site.setting', 'check.gym.visibility'])->group(function(){
            Route::controller(PaymentController::class)->group(function(){
                Route::post('/payments', 'initializePayment');
            });

            Route::prefix('booking')->controller(BookingController::class)->group(function(){
                Route::post('/', 'store');
            });

            Route::prefix('profile')->group(function(){
                Route::controller(UserController::class)->group(function(){
                    Route::get('/', 'profile');
                    Route::put('/', 'update');
                    Route::delete('/', 'destroy');
                });
            });
    
            Route::prefix('logout')->controller(LogoutController::class)->group(function(){
                Route::post('/current', 'logoutFromCurrentSession');
                Route::post('/all',  'logoutFromAllSessions');
                Route::post('/others', 'logoutFromOtherSessions');
            });
        });
    });

    Route::get('/', [GymSelectionController::class, 'index']);

    // User Public - Gym-specific routes
    Route::prefix('{gym:slug}')->middleware(['store.gym.context', 'share.site.setting', 'check.gym.visibility'])->group(function(){
        Route::controller(HomeController::class)->group(function(){
            Route::get('/', 'index')->name('index');
        });

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

        Route::prefix('trainers')->group(function(){
            Route::controller(UserController::class)->group(function(){
                Route::get('/', 'trainers');
                Route::get('/{user}/coach', 'coachProfile');
            });
        });

        Route::resource('memberships', MembershipController::class)->only(['index', 'show']);

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


        Route::prefix('services')->group(function(){
            Route::controller(ServicesController::class)->group(function(){
                Route::get('/', 'index');
                Route::get('/{service}', 'show');
            });
        });

        Route::prefix('invitations')->group(function(){
            Route::controller(InvitationController::class)->group(function(){
                Route::get('/', 'index');
                Route::post('/', 'store');
                Route::post('/{invitation}/verify', 'verify');
                Route::post('/{invitation}/resend', 'resend');
                Route::delete('/{invitation}', 'destroy');
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
                    Route::get('/urls', 'getSharingUrls');
                });
            });
        });

        Route::prefix('gallery')->group(function(){
            Route::controller(GalleryController::class)->group(function(){
                Route::get('/', 'index');
            });
        });

        Route::prefix('site-settings')->group(function(){
            Route::controller(SiteSettingController::class)->group(function(){
                Route::get('/', 'index');
            });
        });

        // Check-in routes
        Route::prefix('checkin')->controller(CheckinController::class)->group(function(){
            Route::post('/self', 'selfCheckin');
            Route::post('/gate', 'gateCheckin');
            Route::get('/personal-qr', 'getPersonalQr');
            Route::get('/history', 'getUserCheckinHistory');
            Route::match(['get', 'post'], '/validate-token', 'validateQrToken');
        });

    });

    Route::post('/payments/intent', [PaymentController::class, 'intent']);
    Route::post('/paymob/webhook', [\App\Http\Controllers\Webhooks\PaymobWebhookController::class, 'handle'])->name('paymob.webhook');
});
