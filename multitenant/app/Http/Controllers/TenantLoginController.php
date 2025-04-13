<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TenantLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('tenant.logintenant');
    }

    public function login(Request $request)
    {
        // Extract subdomain from the request (e.g. 'dsawc.localhost' -> 'dsawc')
        $subdomain = request()->getHost();  
        $subdomain = explode('.', $subdomain)[0];  // Extract 'dsawc'
    
        // Find the tenant by subdomain
        $tenant = Tenant::where('id', $subdomain)->first();
    
        if (!$tenant) {
            return redirect()->back()->withErrors(['subdomain' => 'Tenant not found.']);
        }
    
        // Set tenant database connection dynamically
        $tenantDatabase = $tenant->tenancy_db_name; // tenantdsawc
        Config::set('database.connections.mysql.database', $tenantDatabase);
        DB::purge('mysql'); // Purge any cached connection
        DB::reconnect('mysql'); // Reconnect to the tenant's database
    
        // Fetch the user from the tenant's database
        $user = User::where('email', $request->email)->first();
    
        if (!$user || !Hash::check($request->password, $user->password)) {
            return redirect()->back()->withErrors(['email' => 'Invalid credentials.']);
        }
    
        // Log in the user
        Auth::login($user);
    
        // Redirect user based on their role
        return match($user->role) {
            'admin' => redirect()->route('tenant.admin.dashboard'),
            'employee' => redirect()->route('employee.dashboard'),
            default => redirect()->route('tenant.dashboard'),
        };
    }
}

