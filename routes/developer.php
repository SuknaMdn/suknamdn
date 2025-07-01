<?php
use Illuminate\Support\Facades\Route;
use App\Livewire\Developer\Dashboard\Index;
use App\Livewire\Developer\Auth\Login;
use App\Livewire\Developer\Auth\ForgotPassword;
use App\Livewire\Developer\Auth\ResetPassword;
use App\Livewire\Developer\Dashboard\EditProfile;
use App\Livewire\Developer\Dashboard\Companies\Company;
use App\Livewire\Developer\Dashboard\Companies\EditCompany;
use App\Livewire\Developer\Dashboard\Projects\ProjectPage;
use App\Livewire\Developer\Dashboard\Projects\Projects;
use App\Livewire\Developer\Dashboard\Projects\CreateProject;
use App\Livewire\Developer\Dashboard\Units\ProjectUnits;
use App\Livewire\Developer\Dashboard\Units\CreateUnit;
use App\Livewire\Developer\Dashboard\Units\EditUnit;
use App\Livewire\Developer\Dashboard\Orders\AllOrders;
use App\Livewire\Developer\Dashboard\Orders\Order;
use App\Livewire\Developer\Dashboard\Projects\Fullmap;
use App\Livewire\Developer\Dashboard\Customers\AllCustomers;
use App\Livewire\Developer\Dashboard\Projects\EditProject;
use App\Livewire\Developer\Dashboard\Projects\ProjectForm;

Route::middleware(['auth', 'check.role'])->prefix('developer')->name('developer.')->group(function () {
    // dashboard
    Route::get('/dashboard', Index::class)->name('dashboard');
    Route::get('/profile', EditProfile::class)->name('profile');
    Route::get('/edit-profile', EditProfile::class)->name('edit-profile');

    // companies
    Route::get('/companies', Company::class)->name('companies');
    Route::get('/companies/edit/{id}', EditCompany::class)->name('companies.edit');

    // projects
    Route::get('/projects', Projects::class)->name('projects');
    Route::get('/project/{slug}', ProjectPage::class)->name('projects.show');
    Route::get('/projects/fullmap', Fullmap::class)->name('projects.fullmap');

    // edit project
    Route::get('projects/{project}/edit', EditProject::class)->name('projects.edit');
    // projects/create
    Route::get('/projects/create', CreateProject::class)->name('projects.create');

    // projec units
    Route::get('/projects/units', ProjectUnits::class)->name('projects.units');

    // create unit
    Route::get('/projects/units/create', CreateUnit::class)->name('projects.units.create');
    // edit unit
    Route::get('/projects/units/edit/{id}', EditUnit::class)->name('projects.units.edit');

    // all orders
    Route::get('/orders', AllOrders::class)->name('orders');
    Route::get('/orders/{order}', Order::class)->name('orders.show');

    // group of customers
    Route::group(['prefix' => 'customers'], function () {
        Route::get('/', AllCustomers::class)->name('customers.all');
    });

});

Route::get('developer/login', Login::class)->middleware('guest');
Route::get('developer/forgot-password', ForgotPassword::class)->name('developer.forgot-password');
Route::get('developer/reset-password/{token}', ResetPassword::class)->name('password.reset');
