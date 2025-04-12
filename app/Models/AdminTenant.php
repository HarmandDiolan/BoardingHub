<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $fillable = ['name', 'email', 'tenant_id'];

    // Define the inverse of the relationship
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
