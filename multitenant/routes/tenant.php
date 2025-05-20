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
use App\Http\Controllers\Tenant\ComplaintController;
use App\Http\Controllers\Tenant\UserRoomController;
use App\Http\Controllers\Tenant\AnnouncementController;
use App\Http\Controllers\Tenant\UpdateController;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\TenantPlanController;
use App\Http\Controllers\Tenant\ThemeController;

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
        $themeController = new \App\Http\Controllers\Tenant\ThemeController();
        $theme = $themeController->getThemeSettings();
        return view('tenant.dashboard', compact('theme'));
    });

    Route::get('/whoami', function () {
        return 'Tenant ID: ' . optional(tenant())->id;
    });

    Route::get('/test-github-api', function () {
        $response = Http::get('https://api.github.com/repos/HarmandDiolan/BoardingHub/releases/latest');
    
        if ($response->successful()) {
            Log::info("GitHub response: " . $response->body());
        } else {
            Log::error("GitHub API request failed: " . $response->status());
        }
    
        return response()->json([
            'status' => $response->status(),
            'body' => $response->body(),
        ]);
    });
    
    // Authentication routes
    Route::get('tenant/register', [TenantLoginController::class, 'showRegisterForm'])->name('tenant.register.form');
    Route::post('tenant/register', [TenantLoginController::class, 'TenantRegister'])->name('tenant.register');
    Route::get('/tenant-login', [TenantAuthController::class, 'showLoginForm'])->name('tenant.login');
    Route::post('/tenant-login', [TenantAuthController::class, 'login']);
    Route::post('/tenant-logout', [TenantAuthController::class, 'logout'])->name('tenant.logout');


    // Protected routes
    Route::middleware(['auth'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'index'])->name('tenant.dashboard');
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('tenant.admin.dashboard');
        Route::get('/user/dashboard', [TenantLoginController::class, 'showUserDashboard'])->name('tenant.user.userDashboard');
        Route::get('/admin/users', [AdminController::class, 'users'])->name('tenant.admin.users');
        
        Route::get('/complaints/create', [ComplaintController::class, 'create'])->name('tenant.user.complaints.create');
        Route::post('/complaints', [ComplaintController::class, 'store'])->name('tenant.user.complaints.store');

        Route::get('/admin/complaints', [ComplaintController::class, 'index'])->name('tenant.admin.complaints.index');
        Route::get('/user/rooms', [UserRoomController::class, 'index'])->name('tenant.user.rooms.index');
        Route::post('/user/rooms/rent/{id}', [UserRoomController::class, 'rent'])->name('tenant.user.rooms.rent');

        Route::get('/announcements', [AnnouncementController::class, 'index'])->name('tenant.admin.announcements');
        Route::post('/announcements', [AnnouncementController::class, 'store'])->name('tenant.admin.announcements.store');

        Route::get('/admin/dashboard', [RoomController::class, 'dashboard'])->name('tenant.admin.dashboard');

        Route::get('/check-update', [UpdateController::class, 'check']);
        Route::get('/settings', [AdminController::class, 'settings'])->name('tenant.admin.settings');
        Route::get('/update-system', [UpdateController::class, 'updateSystem']);

        // Theme routes
        Route::get('/admin/theme', [ThemeController::class, 'index'])->name('tenant.admin.theme');
        Route::post('/admin/theme/update', [ThemeController::class, 'updateTheme'])->name('tenant.admin.theme.update');

        // PDF Export routes
        Route::get('/export-pdf/{report_type?}', [\App\Http\Controllers\PdfExportController::class, 'export'])->name('tenant.admin.export-pdf');

        Route::get('/tenant/users/dashboard', [TenantLoginController::class, 'showUserDashboard'])->name('tenant.users.userDashboard');

        // Room management
        Route::prefix('admin/rooms')->group(function () {
            Route::get('/', [RoomController::class, 'index'])->name('tenant.admin.room');
            Route::get('/create', [RoomController::class, 'create'])->name('tenant.admin.room.create');
            Route::post('/', [RoomController::class, 'store'])->name('tenant.admin.room.store');
            Route::get('/{id}/edit', [RoomController::class, 'edit'])->name('tenant.admin.room.edit');
            Route::put('/{id}', [RoomController::class, 'update'])->name('tenant.admin.room.update');
            Route::delete('/{id}', [RoomController::class, 'destroy'])->name('tenant.admin.room.destroy');
            Route::get('/{roomId}/occupant', [RoomController::class, 'showOccupant'])->name('tenant.admin.room.showOccupant');
            
            Route::get('/rentals', [RoomController::class, 'rentalIndex'])->name('tenant.admin.rent.rentalIndex');
            Route::post('/rentals/{id}/mark-paid', [RoomController::class, 'markAsPaid'])->name('tenant.admin.rent.markAsPaid');
            Route::get('/reports', [PdfExportController::class, 'reportIndex'])->name('tenant.admin.reports.index');

            Route::get('/export-pdf', [\App\Http\Controllers\PdfExportController::class, 'export'])->name('tenant.admin.export-pdf');
        });

        Route::post('tenant/rentals/remind/{rentalId}', [RoomController::class, 'sendReminder'])
        ->name('tenant.rentals.remind');
        
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
