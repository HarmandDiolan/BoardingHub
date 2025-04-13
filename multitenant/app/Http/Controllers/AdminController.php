<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // If the user is not authenticated, redirect to the login page
    }
}