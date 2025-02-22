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
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     Route::post('/logout', [AuthController::class, 'logout']);
//     Route::apiResource('colors', ColorController::class);
// });

// Route::middleware("auth:sanctum")->group(function () {
//     Route::post('/logout', [AuthController::class, 'logout']);
//     #Route::resource("colors", ColorController::class);
//     Route::post('colors', [ColorController::class, 'store'])->middleware('permission:edit colors');
//     Route::get('colors', [ColorController::class, 'index']);
// });


// Public routes
// Route::resources([
//     'colors' => ColorController::class,
//     'marineTypes' => MarineTypeController::class,
//     'vehiclemodels' => VehicleModelController::class,
//     'vehiclebrands' => VehicleBrandController::class,
//     'categories' => CategoryController::class,
//     'packages' => PackageController::class,
//     'popularQuestions' => PopularQuestionController::class,
// ], ['only' => ['index', 'show']]);

// // Protected routes
// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::resource('colors', ColorController::class, ['except' => ['index', 'show']])->middleware('permission:edit colors');
//     Route::resource('marineTypes', MarineTypeController::class, ['except' => ['index', 'show']])->middleware('permission:edit marineTypes');
//     Route::resource('vehiclemodels', VehicleModelController::class, ['except' => ['index', 'show']])->middleware('permission:edit vehiclemodels');
//     Route::resource('vehiclebrands', VehicleBrandController::class, ['except' => ['index', 'show']])->middleware('permission:edit vehiclebrands');
//     Route::resource('categories', CategoryController::class, ['except' => ['index', 'show']])->middleware('permission:edit categories');
//     Route::resource('packages', PackageController::class, ['except' => ['index', 'show']])->middleware('permission:edit packages');
//     Route::resource('popularQuestions', PopularQuestionController::class, ['except' => ['index', 'show']])->middleware('permission:edit popularQuestions');
//     Route::apiResource('users', UserController::class)->middleware('permission:edit users');
//     Route::apiResource('roles', RoleController::class)->middleware('permission:edit roles');
//     Route::resource('permissions', PermissionController::class, ['except' => ['store', 'update','destroy']])->middleware('permission:edit roles');
//     Route::resource('subscriptions', SubscribingController::class)->middleware('permission:edit subscribing');
// });


Route::post('register', [AuthController::class, 'register']);
Route::post('verify-account', [AuthController::class, 'verifyAccount']);
Route::post('resend-otp', [AuthController::class, 'resendteOtp']);
Route::post('/login', [AuthController::class, 'loginWithEmailOrPhone']);



 Route::apiResource('colors', ColorController::class);
Route::apiResource('permissions', PermissionController::class);
 Route::apiResource('marineTypes', MarineTypeController::class);
 Route::apiResource('popularQuestions', PopularQuestionController::class);
 Route::apiResource('vehiclemodels', VehicleModelController::class);
 Route::apiResource('vehiclebrands', VehicleBrandController::class);
 Route::apiResource('categories', CategoryController::class);
 Route::apiResource('packages', PackageController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('roles', RoleController::class);
Route::apiResource('subscriptions', SubscribingController::class);
//Route::apiResource('advertisements', AdvertisementController::class);



Route::middleware("auth:sanctum")->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::resource("advertisements", AdvertisementController::class);
    Route::post('/subscription-requests', [SubscriptionRequestController::class, 'store']);
    Route::put('/subscription-requests/{id}/process', [SubscriptionRequestController::class, 'process']);
    Route::get('/subscription-requests', [SubscriptionRequestController::class, 'index']);
    Route::get('/my-subscription', [SubscribingController::class, 'show_my_subscription'])->name('subscriptions.my');
    Route::get('subscriptions/{id}', [SubscribingController::class, 'show'])->whereNumber('id')->name('subscriptions.show');
});
