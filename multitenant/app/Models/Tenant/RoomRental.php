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

    protected $dates = ['due_date'];


    public function user()
    {
        return $this->belongsTo(User::class, 'rented_by');
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    
}
