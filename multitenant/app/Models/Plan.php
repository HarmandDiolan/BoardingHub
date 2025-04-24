<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Plan extends Model
{
    protected $casts = ['features' => 'array'];

    protected $fillable = ['name', 'price', 'features'];

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }
}

