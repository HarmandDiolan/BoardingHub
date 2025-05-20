<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Announcement;
use Illuminate\Support\Facades\Log;


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
    
        // Check if the user is already logged in and redirect accordingly
        if ($user->role === 'admin') {
            return redirect()->route('tenant.admin.dashboard');
        } elseif ($user->role === 'employee') {
            return redirect()->route('employee.dashboard');
        } elseif ($user->role === 'user') {
            return redirect()->route('tenant.users.userDashboard');
        }
    
        return redirect()->route('tenant.dashboard');  // Fallback route
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

            // Log success
            Log::info('User registered successfully: ' . $user->email);

            DB::commit();

            Auth::login($user);

            return redirect()->route('tenant.users.userDashboard')->with('success', 'Account registered successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            // Log the exception
            Log::error('Error registering user: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);

            return redirect()->back()->withErrors(['error' => 'An error occurred while registering the account.']);
        }
    }


    public function showRegisterForm()
    {
        return view('tenant.registertenant');  
    }

    public function showUserDashboard()
    {   
        $announcements = Announcement::where('is_active', true)->latest()->take(5)->get();

        $user = Auth::user();
        return view('tenant.user.userDashboard', compact('announcements'));  
    }
}

