<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Livewire\Developer\Auth\Login;
use App\Livewire\Frontend\ShowProject;
use App\Http\Controllers\Api\PaymentController;
use App\Livewire\Frontend\Auth\DeleteAccountForm;
use App\Livewire\Frontend\HomePage;
use App\Livewire\Frontend\Privacy;

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

Route::middleware('throttle:60,1')->group(function () {

    Route::get('/login', Login::class)->name('login');

    // Route::get('/', function () {
    //     return redirect()->route('login');
    // });

    // Route::get('/', HomePage::class)->name('home');
    Route::get('/privacy', Privacy::class)->name('privacy');
    Route::get('/projects/{slug}', ShowProject::class)->name('projects.show');

    Route::get('/account/deletion', DeleteAccountForm::class)->name('account.deletion');

    Route::get('/cache/all', function () {
        try {
            // Clear all old cache first
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');

            // Generate new cache
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');
            Artisan::call('icons:cache');

            return response()->json([
                'success' => true,
                'message' => 'All cache commands executed successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cache command failed: ' . $e->getMessage()
            ], 500);
        }
    });
    Route::get('/payments/handelreturndata', [PaymentController::class, 'handelReturnData'])->name('payments.testhook');

});

