<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\ProfileController;
use App\Http\Controllers\Api\Auth\PasswordController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\MapController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\FilterController;
/*
|--------------------------------------------------------------------------
| Api Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Api routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/verification/verify/{id}/{hash}', [AuthController::class, 'verify'])
    ->name('verification.verify');

Route::prefix('auth')->group(function () {
    Route::post('/send-otp', [AuthController::class, 'requestOtp']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::put('/profile/update', [ProfileController::class, 'updateProfile']);
    Route::put('/password/reset', [PasswordController::class, 'resetPassword']);
    Route::post('/payments', [PaymentController::class, 'createPayment']);
    // Route::get('/payments/{transactionId}', [PaymentController::class, 'getPayment']);
    Route::get('/payments', [PaymentController::class, 'listPayments']);

    // user address
    Route::get('/addresses/user', [AddressController::class, 'getUserAddress']);

    // createSTCPayment
    Route::get('payments/stc-payment', [PaymentController::class, 'createSTCPayment']); // TODO: remove this

    // favorite
    Route::get('user/favorite/{user_id}', [FavoriteController::class, 'getUserFavorite']);
    Route::post('user/favorite', [FavoriteController::class, 'createFavorite']);
    Route::delete('user/favorite/{id}', [FavoriteController::class, 'deleteFavorite']);
    Route::post('user/toggleFavorite', [FavoriteController::class, 'toggleFavorite']);
    // search
    Route::get('/search', [SearchController::class, 'searchProjects']);

});

Route::get('/addresses/cities', [AddressController::class, 'getCities']);
Route::get('/addresses/areas', [AddressController::class, 'getAreas']);
Route::get('/maps/allprojects', [MapController::class, 'index']);
Route::post('/payments/webhook', [PaymentController::class, 'handleWebhook']);

// filter parameters
Route::get('/projects-filter-parameters', [FilterController::class, 'getProjectsFilterParameters']);
Route::get('/units-filter-parameters/{project_id}', [FilterController::class, 'getUnitsFilterParameters']);
