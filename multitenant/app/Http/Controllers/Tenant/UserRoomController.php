<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant\Room;


class UserRoomController extends Controller
{
    public function index(){

        $rooms = Room::where('status', 'available')->get();
        $rentedRoom = Room::where('rented_by', Auth::id())->where('status', 'occupied')->first();

        return view('tenant.user.rooms', compact('rooms', 'rentedRoom'));
    }

    public function rent($id)
    {
        $room = Room::findOrFail($id);
    
        if ($room->status !== 'available') {
            return back()->with('error', 'Room is already occupied');
        }
    
        $room->update([
            'status' => 'occupied',
            'rented_by' => Auth::id(), 
        ]);
        
        $room->save();
        return back()->with('success', 'You have successfully rented Room ' . $room->room_number);
    }
    
}
