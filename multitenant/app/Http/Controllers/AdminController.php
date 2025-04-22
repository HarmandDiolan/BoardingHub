<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Tenant\Room;

class AdminController extends Controller
{
    public function index()
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            // Get the authenticated user
            $user = Auth::user();
            // Pass the user data to the view
            return view('tenant.admin.dashboard', compact('user'));
        }
    }

    public function users()
    {
        $users = User::where('role', 'user')->with('rentedRoom')->get();
        return view('tenant.admin.user', compact('users'));
    }
    
    
}