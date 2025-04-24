<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string    $requiredPlan
     */
    public function handle(Request $request, Closure $next)
    {
        if (tenant()->plan !== 'pro') {
            return redirect()->route('tenant.upgrade.page')
                             ->with('error', 'Upgrade to Pro to access this feature.');
        }

        return $next($request);
    }
}
