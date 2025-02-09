<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\MarineTypeController;
use App\Http\Controllers\PopularQuestionController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\VehicleModelController;
use App\Http\Controllers\VehicleBrandController;
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
//     return $request->user();
// });

Route::apiResource('colors', ColorController::class);
Route::apiResource('permissions', PermissionController::class);
Route::apiResource('marineTypes', MarineTypeController::class);
Route::apiResource('popularQuestions', PopularQuestionController::class);
Route::apiResource('vehiclemodels', VehicleModelController::class);
Route::apiResource('vehiclebrands', VehicleBrandController::class);

