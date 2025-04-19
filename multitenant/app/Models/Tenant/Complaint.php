<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Complaint extends Model
{
    protected $fillable = ['user_id', 'subject', 'message'];

    protected $table = 'complaints'; 
    
    public function user(){
        return $this->belongsTo(User::class);
    }
}
