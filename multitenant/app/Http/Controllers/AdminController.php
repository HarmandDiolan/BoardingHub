<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Return the admin dashboard view
        return view('tenant.admin.dashboard');
    }
}
