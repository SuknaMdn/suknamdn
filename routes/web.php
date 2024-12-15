<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Livewire\Developer\Auth\Login;
use App\Http\Controllers\Api\PaymentController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/login', Login::class)->name('login');

Route::get('/', function () {
    return redirect()->route('filament.admin.auth.login');
});

Route::get('/payments/webhook', [PaymentController::class, 'handleWebhook']);
