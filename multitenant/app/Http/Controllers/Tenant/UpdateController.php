<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Stancl\Tenancy\Facades\Tenancy;
use Illuminate\Support\Facades\Log;

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

    }

}
