<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\TenantRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TenantPlanController extends Controller
{
    
    public function upgrade(Request $request, $tenantId)
    {
        Log::info('Upgrade request received', [
            'tenant_id' => $tenantId,
            'request_data' => $request->all(),
        ]);

        $request->validate([
            'plan' => 'required|in:free,pro,premium',
        ]);

        Log::info($request->all());
        
        $tenantRequest = TenantRequest::findOrFail($tenantId);
    
        Log::info('TenantRequest record', [
            'id' => $tenantRequest->id,
            'subdomain' => $tenantRequest->subdomain, 
            'current_plan' => $tenantRequest->plan
        ]);
    
        Log::info('Current tenant request plan: ' . $tenantRequest->plan);
    
        if ($tenantRequest->plan === $request->plan) {
            return back()->with('info', 'Tenant is already on this plan');
        }
    
        $tenantRequest->plan = $request->plan;
    
        if ($tenantRequest->save()) {
            Log::info('Updated tenant request plan: ' . $tenantRequest->plan);
        } else {
            Log::error('Failed to update tenant request plan');
            return back()->with('error', 'Failed to update tenant plan');
        }
        
        $tenant = Tenant::find($tenantRequest->subdomain);
        
        if (!$tenant) {
            Log::error('Tenant not found by subdomain, trying ID');
            $tenant = Tenant::find($tenantId);
        }
        
        if ($tenant) {
            try {
                Log::info('Original tenant data', [
                    'tenant_id' => $tenant->id,
                    'data' => $tenant->data
                ]);
                
                $currentData = DB::table('tenants')
                    ->where('id', $tenant->id)
                    ->value('data');
                
                Log::info('Current data from DB query', [
                    'data' => $currentData
                ]);
                
                $newData = $currentData ? json_decode($currentData, true) : [];
                
                $newData['plan'] = $request->plan;
                
                $jsonData = json_encode($newData);
                
                Log::info('About to update with data', [
                    'new_data' => $jsonData
                ]);
                
                $updated = DB::table('tenants')
                    ->where('id', $tenant->id)
                    ->update(['data' => $jsonData]);
                
                Log::info('DB update result', [
                    'updated' => $updated ? 'success' : 'failed'
                ]);
                
                $verifyData = DB::table('tenants')
                    ->where('id', $tenant->id)
                    ->value('data');
                    
                Log::info('Verified data after update', [
                    'data' => $verifyData
                ]);
                
            } catch (\Exception $e) {
                Log::error('Failed to update tenant data', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        } else {
            Log::error('Tenant not found: ' . $tenantRequest->subdomain);
        }
    
        return back()->with('success', 'Tenant upgraded to ' . ucfirst($request->plan));
    }
    
    
}
