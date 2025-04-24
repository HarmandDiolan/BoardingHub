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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Plan;
use Illuminate\Support\Facades\Artisan;


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
            'plan' => $request->plan ?? 'free',

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
    public function edit($subdomain)
    {

    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tenant = Tenant::findOrFail($id);

        $tenant->disabled = !$tenant->disabled;
        $tenant->save();

        return back()->with('success', 'Tenant status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tenant = Tenant::where('id', $id)->first();

        if(!$tenant){
            return back()->with('error', "Tenant ID with {$id} does not exist");
        }

        try{

            $dbName = $tenant->database()->getName();

            tenancy()->initialize($tenant);
            tenancy()->end();

            $tenant->domains()->delete();
            
            $tenant->delete();

            DB::statement("DROP DATABASE IF EXISTS $dbName");

            TenantRequest::where('subdomain', $tenant->id)->delete();

        }catch(\Exception $e){
            return redirect()->route('subdomain.index')->with('error', 'An error occured while deleting the tenant.');
        }
        return redirect()->route('subdomain.index')->with('success', 'Tenant deleted successfully.');
    }
    

    public function approve($id)
    {
        $request = TenantRequest::findOrFail($id);

        if ($request->status === 'approved') {
            return back()->with('info', 'Already approved.');
        }

        $password = 'password'; //if random just change the 'password' to 'Str::random(12)'

        $plan = $request->plan ?? 'free';

        if (!$plan) {
            return back()->with('error', 'Requested plan not available.');
        }

        // Create the actual tenant
        $tenant = Tenant::create([
            'id' => $request->subdomain, 
            'tenancy_db_name' => 'tenant' . Str::ucfirst($request->subdomain), 
            'plan' => $request->plan,
        ]);

        $tenant->domains()->create(['domain' => $request->subdomain . '.localhost']);


        // Initialize tenant's database
        tenancy()->initialize($tenant);

        Artisan::call('tenants:migrate', ['--tenants' => [$tenant->id], '--force' => true]);

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



