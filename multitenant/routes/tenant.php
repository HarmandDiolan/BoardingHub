<?php

declare(strict_types=1);

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\TenantLoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Tenant\RoomController;
use App\Http\Controllers\Tenant\Auth\TenantAuthController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
    // Debug route
    Route::get('/debug-db', function () {
        try {
            $dbName = DB::connection()->getDatabaseName();
            $tables = DB::select('SHOW TABLES');
            $roomsTable = Schema::hasTable('rooms');
            
            $debugInfo = [
                'database' => $dbName,
                'tables' => $tables,
                'has_rooms_table' => $roomsTable,
                'tenant' => tenant()->toArray(),
            ];
            
            Log::info('Database debug info:', $debugInfo);
            
            return response()->json($debugInfo);
        } catch (\Exception $e) {
            Log::error('Database debug error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    });

    // Test route to check tenant context
    Route::get('/test-tenant', function () {
        $debugInfo = [
            'tenant' => tenant() ? tenant()->toArray() : null,
            'current_domain' => request()->getHost(),
        ];
        
        Log::info('Test tenant route debug info:', $debugInfo);
        
        return response()->json($debugInfo);
    });

    // Public routes
    Route::get('/', function () {
        return tenant();
    });

    Route::get('/whoami', function () {
        return 'Tenant ID: ' . optional(tenant())->id;
    });

    // Authentication routes
    Route::get('/register', [TenantAuthController::class, 'showRegistrationForm'])->name('tenant.register');
    Route::post('/register', [TenantAuthController::class, 'register']);
    Route::get('/tenant-login', [TenantAuthController::class, 'showLoginForm'])->name('tenant.login');
    Route::post('/tenant-login', [TenantAuthController::class, 'login']);
    Route::post('/tenant-logout', [TenantAuthController::class, 'logout'])->name('tenant.logout');

    // Protected routes
    Route::middleware(['auth'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'index'])->name('tenant.dashboard');
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('tenant.admin.dashboard');
        
        // Room management
        Route::prefix('admin/rooms')->group(function () {
            Route::get('/', [RoomController::class, 'index'])->name('tenant.admin.room');
            Route::get('/create', [RoomController::class, 'create'])->name('tenant.admin.room.create');
            Route::post('/', [RoomController::class, 'store'])->name('tenant.admin.room.store');
            Route::get('/{id}/edit', [RoomController::class, 'edit'])->name('tenant.admin.room.edit');
            Route::put('/{id}', [RoomController::class, 'update'])->name('tenant.admin.room.update');
            Route::delete('/{id}', [RoomController::class, 'destroy'])->name('tenant.admin.room.destroy');
        });
        
        // User management
        Route::get('users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
    });

    // Debug route
    Route::get('/debug-auth', function () {
        $user = auth()->user();
        $debugInfo = [
            'is_authenticated' => auth()->check(),
            'user' => $user ? [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ] : null,
            'tenant' => tenant() ? tenant()->toArray() : null,
        ];
        
        Log::info('Auth debug info:', $debugInfo);
        
        return response()->json($debugInfo);
    });
});
