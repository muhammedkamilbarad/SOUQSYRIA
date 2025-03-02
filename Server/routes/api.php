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
    Route::get('colors', [ColorController::class, 'index'])->middleware('permission:view_color'); // we shoud talk about
    Route::get('colors/{id}', [ColorController::class, 'show'])->middleware('permission:view_color'); // we should talk about
    Route::post('colors', [ColorController::class, 'store'])->middleware('permission:create_color');
    Route::put('colors/{id}', [ColorController::class, 'update'])->middleware('permission:update_color');
    Route::delete('colors/{id}', [ColorController::class, 'destroy'])->middleware('permission:delete_color');

    // Marine Type Routes
    Route::get('/marineTypes', [MarineTypeController::class, 'index'])->middleware('permission:view_marineTypes');
    Route::get('/marineTypes/{id}', [MarineTypeController::class, 'show'])->middleware('permission:view_marineTypes');
    Route::post('marineTypes', [MarineTypeController::class, 'store'])->middleware('permission:create_marineTypes');
    Route::put('/marineTypes/{id}', [MarineTypeController::class, 'update'])->middleware('permission:update_marineTypes');
    Route::delete('/marineTypes/{id}', [MarineTypeController::class, 'destroy'])->middleware('permission:delete_marineTypes');

    // Vehicle Model Routes
    Route::get('/vehiclemodels', [VehicleModelController::class, 'index'])->middleware('permission:view_vehicleModels');
    Route::get('/vehiclemodels/{id}', [VehicleModelController::class, 'show'])->middleware('permission:view_vehicleModels');
    Route::post('/vehiclemodels', [VehicleModelController::class, 'store'])->middleware('permission:create_vehicleModels');
    Route::put('/vehiclemodels/{id}', [VehicleModelController::class, 'update'])->middleware('permission:update_vehicleModels');
    Route::delete('/vehiclemodels/{id}', [VehicleModelController::class, 'destroy'])->middleware('permission:delete_vehicleModels');

    // Vehicle Brand Routes
    Route::get('/vehiclebrands', [VehicleBrandController::class, 'index'])->middleware('permission:view_vehicleBrands');
    Route::get('/vehiclebrands/{id}', [VehicleBrandController::class, 'show'])->middleware('permission:view_vehicleBrands');
    Route::post('/vehiclebrands', [VehicleBrandController::class, 'store'])->middleware('permission:create_vehicleBrands');
    Route::put('/vehiclebrands/{id}', [VehicleBrandController::class, 'update'])->middleware('permission:update_vehicleBrands');
    Route::delete('/vehiclebrands/{id}', [VehicleBrandController::class, 'destroy'])->middleware('permission:delete_vehicleBrands');

    // User Routes
    Route::get('/users', [UserController::class, 'index'])->middleware('permission:view_user');
    Route::get('/users/{id}', [UserController::class, 'show'])->middleware('permission:view_user');
    Route::post('/users', [UserController::class, 'store'])->middleware('permission:add_user');
    Route::put('/users/{id}', [UserController::class, 'update'])->middleware('permission:update_user');
    // User Soft/Hard Delete Routes
    Route::delete('/users/{id}/soft', [UserController::class, 'softDelete']);
    Route::delete('/users/hard-delete/{id}', [UserController::class, 'destroy']);
    Route::patch('/users/{id}/restore', [UserController::class, 'restore']);


    // Role Routes
    Route::get('/roles', [RoleController::class, 'index'])->middleware('permission:view_role');
    Route::get('/roles/{id}', [RoleController::class, 'show'])->middleware('permission:view_role');
    Route::post('/roles', [RoleController::class, 'store'])->middleware('permission:create_role');
    Route::put('/roles/{id}', [RoleController::class, 'update'])->middleware('permission:update_role');
    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->middleware('permission:delete_role');

    // Permission Routes
    Route::get('permissions', [PermissionController::class, 'index'])->middleware('permission:view_permission');

    // Popular Questions Routes
    Route::get('/popularQuestions', [PopularQuestionController::class, 'index'])->middleware('permission:view_faq');
    Route::get('/popularQuestions/{id}', [PopularQuestionController::class, 'show'])->middleware('permission:view_faq');
    Route::post('/popularQuestions', [PopularQuestionController::class, 'store'])->middleware('permission:create_faq');
    Route::put('/popularQuestions/{id}', [PopularQuestionController::class, 'update'])->middleware('permission:update_faq');
    Route::delete('/popularQuestions/{id}', [PopularQuestionController::class, 'destroy'])->middleware('permission:delete_faq');

    // Package Routes
    Route::get('/packages', [PackageController::class, 'index'])->middleware('permission:view_package');
    Route::get('/packages/{id}', [PackageController::class, 'show'])->middleware('permission:view_package');
    Route::post('/packages', [PackageController::class, 'store'])->middleware('permission:create_package');
    Route::put('/packages/{id}', [PackageController::class, 'update'])->middleware('permission:update_package');
    Route::put('/packages/{id}/deactivate', [PackageController::class, 'deactivate'])->middleware('permission:deactivate_package');

    // Complaints Routes
    Route::get('/complaints', [ComplaintsController::class, 'index'])->middleware('permission:view_complaint');
    Route::get('/complaints/user/{userId?}', [ComplaintController::class, 'getAllComplaintsForUser'])->middleware('permission:view_complaint');
    Route::get('/complaints/advertisement/{advertisementId}', [ComplaintController::class, 'getComplaintsForAdvertisement'])->middleware('permission:view_complaint');
    Route::post('/complaints/advertisement', [ComplaintsController::class, 'complaintAboutAdvertisement']);
    Route::post('/complaints/system', [ComplaintController::class, 'complaintAboutSystem']);
    Route::delete('/complaints/{id}', [ComplaintController::class, 'destroy'])->middleware('permission:delete_complaint');


    // Subscribing Requests Routes
    Route::post('/subscription-requests', [SubscriptionRequestController::class, 'store']);
    Route::put('/subscription-requests/{id}/process', [SubscriptionRequestController::class, 'process']); // we should talk about
    Route::get('/subscription-requests', [SubscriptionRequestController::class, 'index'])->middleware('permission:view_subscription_requests');
    Route::get('/my-subscription', [SubscribingController::class, 'show_my_subscription'])->name('subscriptions.my');
    Route::get('subscriptions/{id}', [SubscribingController::class, 'show'])->whereNumber('id')->name('subscriptions.show');

    // Subscribing Routes
    Route::get('subscriptions', [SubscribingController::class, 'index'])->middleware('permission:view_subscription');
    Route::get('subscriptions/{id}', [SubscribingController::class, 'show'])->middleware('permission:view_subscription');
    Route::post('subscriptions', [SubscribingController::class, 'store'])->middleware('permission:create_subscription');
    Route::put('subscriptions/{id}', [SubscribingController::class, 'update'])->middleware('permission:update_subscription');
    Route::delete('subscriptions/{id}', [SubscribingController::class, 'destroy'])->middleware('permission:delete_subscription');

    // Category Routes
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);

    // Favorite routes
    Route::post('/favorites', [FavoriteController::class, 'addToFavorites']);
    Route::delete('/favorites/{advs_id}', [FavoriteController::class, 'removeFromFavorites'])->whereNumber('advs_id');
    Route::get('/favorites', [FavoriteController::class, 'getUserFavorites']);

    // Advertisement Routes
    Route::get('/advertisements', [AdvertisementController::class, 'index'])->middleware('permission:view_ad');
    Route::get('/advertisements/{id}', [AdvertisementController::class,'show'])->middleware('permission:view_ad');
    Route::get('/advertisements-by-user/{id}', [AdvertisementController::class, 'getUserAdvertisements'])->middleware('permission:view_ad');
    Route::post('/advertisements', [AdvertisementController::class,'store'])->middleware('permission:create_ad');
    Route::put('/advertisements/{id}', [AdvertisementController::class, 'update'])->middleware('permission:update_ad');
    Route::delete('/advertisements/{id}', [AdvertisementController::class, 'destroy'])->middleware('permission:delete_ad');
    Route::post('/advertisements/process', [AdvertisementController::class, 'process'])->middleware('permission:process_ad');

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
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
