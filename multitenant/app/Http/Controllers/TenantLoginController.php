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
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user || !Hash::check($request->password, $user->password)) {
            return redirect()->back()->withErrors(['email' => 'Invalid credentials.']);
        }
    
        Auth::login($user);
    
        return match($user->role) {
            'admin' => redirect()->route('tenant.admin.dashboard'),
            'employee' => redirect()->route('employee.dashboard'),
            'user' => redirect()->route('tenant.user.userDashboard'),
        };
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('tenant.login');
    }

    public function TenantRegister(Request $request)
    {
        if (!tenant()) {
            return redirect()->back()->withErrors(['error' => 'Tenant not identified.']);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user',
            ]);

            DB::commit();

            Auth::login($user);

            return redirect()->route('tenant.user.dashboard')->with('success', 'Tenant registered successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['error' => 'An error occurred while registering the tenant.']);
        }
    }


    public function showRegisterForm()
    {
        return view('tenant.registertenant');  // Return the registration view
    }
}

