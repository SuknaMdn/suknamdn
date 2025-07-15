<?php

use App\Http\Controllers\Api\AboutSuknaController;
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
use App\Http\Controllers\Api\MainpageController;
use App\Http\Controllers\Api\NafathController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\Payment\UnitPaymentController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\User\InstallmentController;
use App\Http\Controllers\Api\User\SupportTicketController;

Route::middleware(['auth:sanctum','throttle:60,1'])->group(function () {

    Route::put('/profile/update', [ProfileController::class, 'updateProfile']);
    Route::put('/password/reset', [PasswordController::class, 'resetPassword']);

    // نقطة النهاية للشاشة الرئيسية
    Route::get('/mainpage', MainpageController::class);
    // نقطة النهاية لتفاصيل الحجز الواحد
    Route::get('/reservations/{unitOrder}', ReservationController::class);
    // remove acount
    Route::delete('/account/destroy', [ProfileController::class, 'destroy']);

    Route::post('/payments', [PaymentController::class, 'createPayment']);
    Route::get('/payments', [PaymentController::class, 'listPayments']);
    Route::get('/payments/{id}', [PaymentController::class, 'getPayment']);
    Route::get('/payments-list', [PaymentController::class, 'listPayments']);

    // callback to handle payment response from app
    Route::post('/payments/handlePaymentDueToUnitReservation', [UnitPaymentController::class, 'handlePaymentDueToUnitReservation']);

    // check if unit is reserved
    Route::get('/units/{unit_id}/find', [UnitPaymentController::class, 'findAndValidateUnit']);
    // user address
    Route::get('/addresses/user', [AddressController::class, 'getUserAddress']);

    // createSTCPayment
    // Route::get('payments/stc-payment', [PaymentController::class, 'createSTCPayment']); // TODO: remove this

    // favorite
    Route::get('user/favorite/{user_id}', [FavoriteController::class, 'getUserFavorite']);
    Route::post('user/favorite', [FavoriteController::class, 'createFavorite']);
    Route::delete('user/favorite/{id}', [FavoriteController::class, 'deleteFavorite']);
    Route::post('user/toggleFavorite', [FavoriteController::class, 'toggleFavorite']);

    // nafath auth
    Route::prefix('nafath')->group(function () {
        Route::post('/create-mfa-request', [NafathController::class, 'createMfaRequest']);
        Route::post('/get-mfa-status', [NafathController::class, 'getMfaRequestStatus']);
        Route::post('/callback', [NafathController::class, 'handleCallback']);
    });

    // notifications
    Route::get('/notifications/{id}', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'makeAsRead']);
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'makeAllAsRead']);

    // Sending notifications
    Route::post('/notifications/send-to-user', [NotificationController::class, 'sendToUser']);
    Route::post('/notifications/send-to-multiple', [NotificationController::class, 'sendToMultipleUsers']);

    // unit value for unit reservation
    Route::get('/unit-value-for-unit-reservation', [AboutSuknaController::class, 'getUnitValueForUnitReservation']);

    // installments
    Route::post('/installments/{installment}/upload-receipt', [InstallmentController::class, 'uploadReceipt']);

    // Support Statistics
    // Route::get('/support/stats', [SupportTicketController::class, 'index']);
    // Support Tickets
    Route::get('/support/tickets', [SupportTicketController::class, 'index']);
    Route::post('/support/tickets', [SupportTicketController::class, 'store']);
    Route::get('/support/tickets/{id}', [SupportTicketController::class, 'show']);
    Route::post('/support/tickets/{id}/close', [SupportTicketController::class, 'close']);
    
    // Support Messages
    Route::get('/support/tickets/{ticketId}/messages', [SupportTicketController::class, 'getMessages']);
    Route::post('/support/tickets/{ticketId}/messages', [SupportTicketController::class, 'addMessage']);

});

Route::middleware('throttle:60,1')->group(function () {

    Route::get('/verification/verify/{id}/{hash}', [AuthController::class, 'verify'])->name('verification.verify');

    Route::prefix('auth')->group(function () {
        Route::post('/send-otp', [AuthController::class, 'requestOtp']);
        Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    });

    Route::get('/addresses/cities', [AddressController::class, 'getCities']);
    Route::get('/addresses/areas', [AddressController::class, 'getAreas']);
    Route::get('/maps/allprojects', [MapController::class, 'index']);

    // payment webhook
    Route::post('/payments/webhook', [PaymentController::class, 'handleWebhook']);

    // filter parameters
    Route::get('/projects-filter-parameters', [FilterController::class, 'getProjectsFilterParameters']);
    Route::get('/units-filter-parameters/{project_id}', [FilterController::class, 'getUnitsFilterParameters']);


    Route::get('/content/about', [AboutSuknaController::class, 'about']);
    Route::get('/content/term_and_condition', [AboutSuknaController::class, 'term_and_condition']);
    Route::get('/content/privacy_policy', [AboutSuknaController::class, 'privacy_policy']);
    Route::get('/content/project_ownership', [AboutSuknaController::class, 'project_ownership']);

    // search
    Route::get('/search', [SearchController::class, 'searchProjects']);

});

