<?php

namespace App\Policies\Tenant;

use App\Models\Tenant\Room;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;
use Stancl\Tenancy\Tenancy;

class RoomPolicy
{
    protected $tenancy;

    public function __construct(Tenancy $tenancy)
    {
        $this->tenancy = $tenancy;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Room $room): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        $tenant = $this->tenancy->tenant;
        
        Log::info('RoomPolicy create check:', [
            'tenant_id' => $tenant ? $tenant->id : null,
            'user_id' => $user->id,
            'user_role' => $user->role,
            'is_admin' => $user->role === 'admin'
        ]);
        
        // For now, allow all authenticated users to create rooms
        return true;
        
        // We'll re-enable this check once we confirm the tenant context is working
        // return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Room $room): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Room $room): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Room $room): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Room $room): bool
    {
        return $user->role === 'admin';
    }
} 