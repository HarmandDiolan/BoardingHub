<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\TenantRequest;
use App\Http\Requests\StoreTenantRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminPasswordMail;

class SubdomainController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requests = TenantRequest::orderBy('created_at', 'desc')->get();

        return view('central.requests', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTenantRequest $request)
    {
        TenantRequest::create([
            'name' => $request->name,
            'email' => $request->email,
            'subdomain' => $request->subdomain,
        ]);
    
        return back()->with('success', 'Your request has been submitted and is pending approval.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function approve($id)
    {
        $request = TenantRequest::findOrFail($id);

        if ($request->status === 'approved') {
            return back()->with('info', 'Already approved.');
        }

        $password = 'password';

        // Create the actual tenant
        $tenant = Tenant::create(['id' => $request->subdomain, 'tenancy_db_name' => 'tenant' . Str::ucfirst($request->subdomain)]);
        $tenant->domains()->create(['domain' => $request->subdomain . '.localhost']);

        // Initialize tenant's database
        tenancy()->initialize($tenant);

        // Create the admin user for the tenant
        $admin = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'role' => 'admin',
        ]);

        // Send admin credentials email
        $domain = $request->subdomain . '.localhost:8000';
        Mail::to($request->email)->send(new AdminPasswordMail($request->name, $password, $domain, $request->email));

        // Mark the tenant request as approved
        $request->status = 'approved';
        $request->save();

        // End tenant context
        tenancy()->end();

        return back()->with('success', 'Tenant approved and database created.');
    }
}



