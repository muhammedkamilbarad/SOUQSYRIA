<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\MarineTypeController;
use App\Http\Controllers\PopularQuestionController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\VehicleModelController;
use App\Http\Controllers\VehicleBrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SubscribingController;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\SubscriptionRequestController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ComplaintController;
use App\Http\Middleware\ThrottleLogins;

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    // Color Routes
    Route::group(['prefix' => 'colors'], function () {
        Route::get('/', [ColorController::class, 'index'])->middleware('permission:view_color');
        Route::get('/{id}', [ColorController::class, 'show'])->middleware('permission:view_color');
        Route::post('/', [ColorController::class, 'store'])->middleware('permission:create_color');
        Route::put('/{id}', [ColorController::class, 'update'])->middleware('permission:update_color');
        Route::delete('/{id}', [ColorController::class, 'destroy'])->middleware('permission:delete_color');
    });

    // Marine Type Routes
    Route::group(['prefix' => 'marineTypes'], function () {
        Route::get('/', [MarineTypeController::class, 'index'])->middleware('permission:view_marineTypes');
        Route::get('/{id}', [MarineTypeController::class, 'show'])->middleware('permission:view_marineTypes');
        Route::post('/', [MarineTypeController::class, 'store'])->middleware('permission:create_marineTypes');
        Route::put('/{id}', [MarineTypeController::class, 'update'])->middleware('permission:update_marineTypes');
        Route::delete('/{id}', [MarineTypeController::class, 'destroy'])->middleware('permission:delete_marineTypes');
    });

    // Vehicle Model Routes
    Route::group(['prefix' => 'vehiclemodels'], function () {
        Route::get('/', [VehicleModelController::class, 'index'])->middleware('permission:view_vehicleModels');
        Route::get('/{id}', [VehicleModelController::class, 'show'])->middleware('permission:view_vehicleModels');
        Route::post('/', [VehicleModelController::class, 'store'])->middleware('permission:create_vehicleModels');
        Route::put('/{id}', [VehicleModelController::class, 'update'])->middleware('permission:update_vehicleModels');
        Route::delete('/{id}', [VehicleModelController::class, 'destroy'])->middleware('permission:delete_vehicleModels');
    });

    // Vehicle Brand Routes
    Route::group(['prefix' => 'vehiclebrands'], function () {
        Route::get('/', [VehicleBrandController::class, 'index'])->middleware('permission:view_vehicleBrands');
        Route::get('/{id}', [VehicleBrandController::class, 'show'])->middleware('permission:view_vehicleBrands');
        Route::post('/', [VehicleBrandController::class, 'store'])->middleware('permission:create_vehicleBrands');
        Route::put('/{id}', [VehicleBrandController::class, 'update'])->middleware('permission:update_vehicleBrands');
        Route::delete('/{id}', [VehicleBrandController::class, 'destroy'])->middleware('permission:delete_vehicleBrands');
    });

    // User Routes
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [UserController::class, 'index'])->middleware('permission:view_user');
        Route::get('/{id}', [UserController::class, 'show'])->middleware('permission:view_user');
        Route::post('/', [UserController::class, 'store'])->middleware('permission:add_user');
        Route::put('/{id}', [UserController::class, 'update'])->middleware('permission:update_user');
        Route::delete('/{id}/soft', [UserController::class, 'softDelete'])->middleware('permission:delete_user');
        Route::delete('/hard-delete/{id}', [UserController::class, 'destroy'])->middleware('permission:delete_user');
        Route::patch('/{id}/restore', [UserController::class, 'restore']); // we should talk about
    });

    // Role Routes
    Route::group(['prefix' => 'roles'], function () {
        Route::get('/', [RoleController::class, 'index'])->middleware('permission:view_role');
        Route::get('/{id}', [RoleController::class, 'show'])->middleware('permission:view_role');
        Route::post('/', [RoleController::class, 'store'])->middleware('permission:create_role');
        Route::put('/{id}', [RoleController::class, 'update'])->middleware('permission:update_role');
        Route::delete('/{id}', [RoleController::class, 'destroy'])->middleware('permission:delete_role');
    });

    // Permission Routes
    Route::group(['prefix' => 'permissions'], function () {
        Route::get('/', [PermissionController::class, 'index'])->middleware('permission:view_permission');
    });

    // Popular Questions Routes
    Route::group(['prefix' => 'popularQuestions'], function () {
        Route::get('/', [PopularQuestionController::class, 'index'])->middleware('permission:view_faq');
        Route::get('/{id}', [PopularQuestionController::class, 'show'])->middleware('permission:view_faq');
        Route::post('/', [PopularQuestionController::class, 'store'])->middleware('permission:create_faq');
        Route::put('/{id}', [PopularQuestionController::class, 'update'])->middleware('permission:update_faq');
        Route::delete('/{id}', [PopularQuestionController::class, 'destroy'])->middleware('permission:delete_faq');
    });

    // Package Routes
    Route::group(['prefix' => 'packages'], function () {
        Route::get('/', [PackageController::class, 'index'])->middleware('permission:view_package');
        Route::get('/{id}', [PackageController::class, 'show'])->middleware('permission:view_package');
        Route::post('/', [PackageController::class, 'store'])->middleware('permission:create_package');
        Route::put('/{id}', [PackageController::class, 'update'])->middleware('permission:update_package');
        Route::put('/{id}/deactivate', [PackageController::class, 'deactivate'])->middleware('permission:deactivate_package');
    });

    // Complaints Routes
    Route::group(['prefix' => 'complaints'], function () {
        Route::get('/', [ComplaintsController::class, 'index'])->middleware('permission:view_complaint');
        Route::get('/user/{userId?}', [ComplaintController::class, 'getAllComplaintsForUser'])->middleware('permission:view_complaint');
        Route::get('/advertisement/{advertisementId}', [ComplaintController::class, 'getComplaintsForAdvertisement'])->middleware('permission:view_complaint');
        Route::post('/advertisement', [ComplaintsController::class, 'complaintAboutAdvertisement']);
        Route::post('/system', [ComplaintController::class, 'complaintAboutSystem']);
        Route::delete('/{id}', [ComplaintController::class, 'destroy'])->middleware('permission:delete_complaint');
    });

    // Subscription Requests Routes
    Route::group(['prefix' => 'subscription-requests'], function () {
        Route::post('/', [SubscriptionRequestController::class, 'store']);
        Route::put('/{id}/process', [SubscriptionRequestController::class, 'process'])->middleware('permission:process_subscription_requests');
        Route::get('/', [SubscriptionRequestController::class, 'index'])->middleware('permission:view_subscription_requests');
    });

    // Subscribing Routes
    Route::group(['prefix' => 'subscriptions'], function () {
        Route::get('/', [SubscribingController::class, 'index'])->middleware('permission:view_subscription');
        Route::get('/{id}', [SubscribingController::class, 'show'])->middleware('permission:view_subscription');
        Route::post('/', [SubscribingController::class, 'store'])->middleware('permission:create_subscription');
        Route::put('/{id}', [SubscribingController::class, 'update'])->middleware('permission:update_subscription');
        Route::delete('/{id}', [SubscribingController::class, 'destroy'])->middleware('permission:delete_subscription');
        Route::get('/my-subscription', [SubscribingController::class, 'show_my_subscription'])->name('subscriptions.my');
    });

    // Favorite Routes
    Route::group(['prefix' => 'favorites'], function () {
        Route::post('/', [FavoriteController::class, 'addToFavorites']);
        Route::delete('/{advs_id}', [FavoriteController::class, 'removeFromFavorites'])->whereNumber('advs_id');
        Route::get('/', [FavoriteController::class, 'getUserFavorites']);
    });

    // Category Routes
    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/{id}', [CategoryController::class, 'show']);
    });

    // Advertisement Routes
    Route::group(['prefix' => 'advertisements'], function () {
        Route::get('/', [AdvertisementController::class, 'index'])->middleware('permission:view_ad');
        Route::get('/{id}', [AdvertisementController::class, 'show'])->middleware('permission:view_ad');
        Route::get('/by-user/{id}', [AdvertisementController::class, 'getUserAdvertisements'])->middleware('permission:view_ad');
        Route::post('/', [AdvertisementController::class, 'store'])->middleware('permission:create_ad');
        Route::put('/{id}', [AdvertisementController::class, 'update'])->middleware('permission:update_ad');
        Route::delete('/{id}', [AdvertisementController::class, 'destroy'])->middleware('permission:delete_ad');
        Route::post('/process', [AdvertisementController::class, 'process'])->middleware('permission:process_ad');
    });

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [UserController::class, 'getProfile']);
});


// Authentication routes with specific rate limiting parameters
Route::post('/register', [AuthController::class, 'register'])
    ->middleware('throttle.login:3,10') // 3 attempts, 10 minute decay
    ->name('auth.register');

Route::post('/login', [AuthController::class, 'loginWithEmailOrPhone'])
    ->middleware('throttle.login:5,10') // 5 attempts, 10 minute decay
    ->name('auth.login');

Route::post('/refresh', [AuthController::class, 'refreshToken'])
    ->middleware('throttle.login:10,1') // 10 attempts, 1 minute decay
    ->name('auth.refresh');

Route::post('/resend-otp', [AuthController::class, 'resendOtp'])
    ->middleware('throttle.login:3,15') // 3 attempts, 15 minute decay
    ->name('auth.resend-otp');

Route::post('/verify-account', [AuthController::class, 'verifyAccount']);
