<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubdomainController;
use App\Http\Controllers\TenantLoginController;
use App\Http\Controllers\AdminController;

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        Route::get('/', fn() => view('welcome'));
        Route::get('/dashboard', fn() => view('dashboard'))->middleware(['auth', 'verified'])->name('dashboard');
        
        // Subdomain request routes
        Route::get('/subdomain-requests', [SubdomainController::class, 'index'])->name('subdomain.index');
        Route::post('/subdomain/approve/{id}', [SubdomainController::class, 'approve'])->name('subdomain.approve');
        Route::post('subdomain', [SubdomainController::class,'store'])->name('subdomain.store');
        

        Route::domain('tenant.localhost')->middleware(['auth'])->group(function () {
            Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('tenant.admin.dashboard');
        });
        
        // Tenant login routes

    });
}

// Authenticated user profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';

