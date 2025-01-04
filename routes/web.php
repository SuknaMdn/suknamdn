<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Livewire\Developer\Auth\Login;
use App\Livewire\Frontend\ShowProject;
use Illuminate\Support\Facades\Cache;
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

Route::get('/projects/{slug}', ShowProject::class)->name('projects.show');

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
