<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\TenantRequest;
use Illuminate\Support\Facades\Log;

class TenantPlanController extends Controller
{
    
    public function upgrade(Request $request, $tenantId)
    {
        $request->validate([
            'plan' => 'required|in:free,pro,premium',
        ]);

        Log::info($request->all());
        
        // Fetch the tenant by ID
        $tenant = TenantRequest::findOrFail($tenantId);
    
        // Log current tenant plan
        Log::info('Current tenant plan: ' . $tenant->plan);
    
        if ($tenant->plan === $request->plan) {
            return back()->with('info', 'Tenant is already on this plan');
        }
    
        // Update the tenant plan
        $tenant->plan = $request->plan;
    
        // Save the tenant model
        if ($tenant->save()) {
            Log::info('Updated tenant plan: ' . $tenant->plan);
        } else {
            Log::error('Failed to update tenant plan');
        }
    
        return back()->with('success', 'Tenant upgraded to ' . ucfirst($request->plan));
    }
    
    
    
}
