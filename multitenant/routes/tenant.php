<?php

declare(strict_types=1);

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\TenantLoginController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/
Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', function () {
        return tenant();
    });

    Route::get('/whoami', function () {
        return 'Tenant ID: ' . optional(tenant())->id;
    });

    Route::get('tenant-login', [TenantLoginController::class, 'showLoginForm'])->name('tenant.login');
    Route::post('tenant-login', [TenantLoginController::class, 'login'])->name('tenant.login.submit');
    Route::get('tenant-login', [TenantLoginController::class, 'showLoginForm'])->name('tenant.login');

    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('tenant.admin.dashboard');

    Route::get('tenant/register', [TenantLoginController::class, 'showRegisterForm'])->name('tenant.register.form');
    Route::post('tenant/register', [TenantLoginController::class, 'TenantRegister'])->name('tenant.register');

    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users', [UserController::class, 'store'])->name('users.store');

});