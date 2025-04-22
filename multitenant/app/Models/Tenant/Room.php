<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Tenant\RoomRental; 

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number',
        'capacity',
        'price',
        'status',
        'rented_by',

    ];

    protected $connection = 'tenant';

    public function occupant()
    {
        return $this->belongsTo(User::class, 'rented_by');
    }
    
    public function rentedRoom()
    {
        return $this->hasOne(\App\Models\Tenant\Room::class, 'rented_by');
    }

    public function rentRoom()
    {
        return $this->hasMany(RoomRental::class, 'room_id'); // Assuming 'room_id' is the foreign key
    }
    
}
