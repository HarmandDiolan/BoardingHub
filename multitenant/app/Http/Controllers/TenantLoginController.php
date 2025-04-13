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
    public function TenantRegister(Request $request)
    {
        // Validate the incoming request
            $request->validate([
                'email' => 'required|email|unique:users,email', // Unique email
                'password' => 'required|string|min:8|confirmed', // Password and confirmation
            ]);

            // Start a database transaction
            DB::beginTransaction();

            try {
                // Create a new tenant record
                $tenant = Tenant::create([
                    'tenancy_db_name' => 'tenant_' . $request->subdomain, // Name of tenant's database (e.g. tenant_dsawc)
                ]);

                // Create a new user record for the admin (assuming the admin is the first user)
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => 'admin', // Default role for the first user is admin
                ]);

                // Optionally, associate the user with the tenant (you may have a tenant_user pivot table)
                // $tenant->users()->attach($user->id);

                // Commit the transaction
                DB::commit();

                // Optionally, log in the user immediately
                Auth::login($user);

                // Redirect to the admin dashboard or another route
                return redirect()->route('tenant.admin.dashboard')->with('success', 'Tenant registered successfully!');
            } catch (\Exception $e) {
                // Rollback the transaction in case of an error
                DB::rollBack();

                // Handle the error
                return redirect()->back()->withErrors(['error' => 'An error occurred while registering the tenant.']);
            }
    }
    public function showRegisterForm()
    {
        return view('tenant.registertenant');  // Return the registration view
    }
}

