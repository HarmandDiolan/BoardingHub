<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Tenant\Room;

class RoomRental extends Model
{
    protected $fillable = [
        'room_id',        
        'rented_by',      
        'price',         
        'due_date',      
        'payment_status', 
    ];

    protected $connection = 'tenant';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    
}
