<?php

namespace App\Http\Controllers;

use App\Models\TenantRequest;
use Illuminate\Http\Request;
use Stancl\Tenancy\Tenant;
use Illuminate\Support\Facades\Log;

class TenantPlanController extends Controller
{
    public function upgrade(Request $request, $tenantId)
    {
        $request->validate([
            'plan' => 'required|in:free,pro,premium',
        ]);

        Log::info('Upgrade request:', $request->all());

        // Fetch the actual tenant by ID
        $tenant = TenantRequest::findOrFail($tenantId);

        // Log current tenant plan
        $currentPlan = $tenant->get('plan');
        Log::info('Current tenant plan: ' . $currentPlan);

        // Check if already on the same plan
        if ($currentPlan === $request->plan) {
            return back()->with('info', 'Tenant is already on the ' . ucfirst($request->plan) . ' plan.');
        }

        // Update the tenant's plan
        $tenant->put('plan', $request->plan);

        // Save the tenant model
        if ($tenant->save()) {
            Log::info('Tenant upgraded to: ' . $request->plan);
            return back()->with('success', 'Tenant upgraded to ' . ucfirst($request->plan));
        } else {
            Log::error('Failed to update tenant plan');
            return back()->with('error', 'Failed to update tenant plan.');
        }
    }
}
