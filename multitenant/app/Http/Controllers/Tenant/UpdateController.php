<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Stancl\Tenancy\Facades\Tenancy;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class UpdateController extends Controller
{
    public function check()
    {
        // Fetch the current tenant version
        $tenantVersion = config('app.version'); // You can replace this with tenant-specific version if needed
    
        // Example: Fetch the latest release info from GitHub API or another source
        try {
            $response = Http::get('https://api.github.com/repos/HarmandDiolan/BoardingHub/releases/latest');
    
            if ($response->successful()) {
                // Extract the latest version
                $latestVersion = $response->json()['tag_name'];
    
                // Compare the tenant's current version with the latest version
                return response()->json([
                    'update_available' => version_compare($tenantVersion, $latestVersion, '<'),
                    'latest_version' => $latestVersion,
                ]);
            } else {
                // Log the error response status for debugging
                Log::error('GitHub API error', ['status' => $response->status(), 'response' => $response->body()]);
                return response()->json(['error' => 'Error fetching update information.'], 500);
            }
        } catch (\Exception $e) {
            Log::error('Exception while checking for updates', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'There was an error checking for updates.'], 500);
        }
    }

    public function updateSystem()
    {
        try {
            // Log the start of the update process
            Log::info('Update process started.');
    
            // Access the current tenant through the tenant() helper
            $tenant = tenant();
    
            // Check if the tenant exists
            if (!$tenant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tenant not found or invalid tenant context.',
                ], 400);
            }
    
            // Run the tenants:migrate command to update the tenant database
            $migrateResult = Artisan::call('tenants:migrate');  // This is the correct command for multi-tenant migrations
            $migrateOutput = Artisan::output();
            
            Log::info('Migration result: ' . $migrateOutput);
    
            // Run other necessary commands
            Artisan::call('optimize');
            $optimizeOutput = Artisan::output();
    
            Artisan::call('config:cache');
            $configCacheOutput = Artisan::output();
    
            Artisan::call('route:cache');
            $routeCacheOutput = Artisan::output();
    
            // Log all outputs for debugging
            Log::info('Optimization result: ' . $optimizeOutput);
            Log::info('Config cache result: ' . $configCacheOutput);
            Log::info('Route cache result: ' . $routeCacheOutput);
    
            // Optionally update the tenant's version
            $tenant->update(['version' => 'new_version_here']);  // Replace with actual version
            Log::info('Tenant version updated.');
    
            return response()->json([
                'success' => true,
                'message' => 'System updated successfully.',
            ]);
        } catch (\Exception $e) {
            // Log detailed error for debugging
            Log::error('Error during system update: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'There was an error while updating the system.',
            ], 500);
        }
    }
    
    
}
