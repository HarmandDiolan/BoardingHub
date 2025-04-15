<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Stancl\Tenancy\Tenancy;

class PreventDisabledTenantAccess
{
    public function handle(Request $request, Closure $next)
    {
        $tenant = tenancy()->tenant;

        if ($tenant && $tenant->disabled) {
            abort(403, 'This tenant is disabled.');
        }

        return $next($request);
    }
    public function revert()
    {
        // No actions needed on revert
    }
}
