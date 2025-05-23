<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantRequest extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'subdomain', 'status'];

    public function tenant(){

        return $this->hasOne(Tenant::class, 'id', 'subdomain', 'subdomain');
    }

    

}
