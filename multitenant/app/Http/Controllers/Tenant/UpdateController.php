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
            Log::info('Update process started.');

            $tenant = tenant();

            if (!$tenant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tenant not found or invalid tenant context.',
                ], 400);
            }

            // Step 1: Get latest release tag from GitHub
            $response = Http::get('https://api.github.com/repos/HarmandDiolan/BoardingHub/releases/latest');
            if (!$response->successful()) {
                Log::error('GitHub API error', ['status' => $response->status(), 'response' => $response->body()]);
                return response()->json(['error' => 'Error fetching update information.'], 500);
            }

            $latestVersion = $response->json()['tag_name'];
            Log::info("Latest version from GitHub: $latestVersion");

            // Step 2: Pull latest code and checkout to the tag
            $basePath = base_path();
            exec("cd $basePath && git fetch --tags 2>&1", $gitFetchOutput, $gitFetchStatus);
            exec("cd $basePath && git checkout $latestVersion 2>&1", $gitCheckoutOutput, $gitCheckoutStatus);

            Log::info('Git Fetch Output: ' . implode("\n", $gitFetchOutput));
            Log::info('Git Checkout Output: ' . implode("\n", $gitCheckoutOutput));

            if ($gitCheckoutStatus !== 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Git checkout failed. Check logs for details.',
                ], 500);
            }

            // Step 3: Run composer install
            exec("cd $basePath && composer install --no-dev --optimize-autoloader 2>&1", $composerOutput, $composerStatus);
            Log::info('Composer Output: ' . implode("\n", $composerOutput));

            if ($composerStatus !== 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Composer install failed. Check logs for details.',
                ], 500);
            }

            // Step 4: Run tenant migration
            Artisan::call('tenants:migrate', [
                '--tenants' => [$tenant->id],
                '--force' => true,
            ]);
            Log::info('Migration result: ' . Artisan::output());

            // Step 5: Optimize app
            Artisan::call('optimize');
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Log::info('Optimization and cache cleared.');

            // Step 6: Update tenant version in DB
            $tenant->update(['version' => $latestVersion]);
            Log::info('Tenant version updated to ' . $latestVersion);

            return response()->json([
                'success' => true,
                'message' => 'System updated to version ' . $latestVersion,
            ]);
        } catch (\Exception $e) {
            Log::error('Error during system update: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'There was an error while updating the system.',
            ], 500);
        }
    }

}
