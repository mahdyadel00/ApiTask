<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdController;
use App\Http\Controllers\PlaceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1', 'namespace' => 'api'], function () {

    Route::any('cache/clear', [AuthController::class, 'clearCache']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('check-email', [AuthController::class, 'checkEmail']);
    Route::post('check-code', [AuthController::class, 'checkCode']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
});

Route::group(['middleware' => 'auth:api', 'namespace' => 'Api', 'prefix' => 'v1'], function () {

    Route::get('user/get/profile', [UserController::class, 'getProfile']);
    Route::post('user/update/profile', [UserController::class, 'updateProfile']);
    Route::post('user/update/password', [UserController::class, 'updatePassword']);
    Route::post('user/update/personal', [UserController::class, 'updatePersonalProfile']);


    Route::get('ads', [AdController::class, 'index']);
    Route::post('ads/store', [AdController::class, 'store']);
    Route::post('ads/update/{id}', [AdController::class, 'update']);
    Route::post('ads/delete/{id}', [AdController::class, 'destroy']);

    Route::get('place', [PlaceController::class, 'index']);
    Route::post('place/store', [PlaceController::class, 'store']);
    Route::post('place/update/{id}', [PlaceController::class, 'update']);
    Route::post('place/delete/{id}', [PlaceController::class, 'destroy']);
});
