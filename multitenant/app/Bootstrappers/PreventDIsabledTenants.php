<?php

namespace App\Bootstrappers;

use Stancl\Tenancy\Contracts\TenancyBootstrapper;
use Stancl\Tenancy\Contracts\Tenant;

class PreventDisabledTenants implements TenancyBootstrapper
{
    public function bootstrap(Tenant $tenant)
    {
        // Check if the tenant has a `disabled` attribute
        if (property_exists($tenant, 'disabled') && $tenant->disabled) {
            abort(403, 'This tenant is disabled.');
        }
    }

    public function revert()
    {
        // Optional: revert anything if needed when tenancy ends
    }
}
