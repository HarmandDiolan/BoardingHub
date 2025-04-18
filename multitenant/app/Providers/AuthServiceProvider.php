<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Tenant\Room;
use App\Policies\Tenant\RoomPolicy;
use Illuminate\Support\Facades\Log;


class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Room::class => RoomPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
        Log::info('RoomPolicy is loaded.');
    }
}
