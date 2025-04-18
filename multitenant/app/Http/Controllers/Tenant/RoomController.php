<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StoreRoomRequest;
use App\Http\Requests\Tenant\UpdateRoomRequest;
use Illuminate\Http\Request;
use App\Models\Tenant\Room;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Policies\Tenant\RoomPolicy;
use Stancl\Tenancy\Tenancy;
use Stancl\Tenancy\Contracts\Tenant;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    protected $tenancy;

    public function __construct(Tenancy $tenancy)
    {
        $this->tenancy = $tenancy;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Debug tenant database connection
        try {
            $dbName = DB::connection()->getDatabaseName();
            Log::info('Current database:', ['database' => $dbName]);
            
            $rooms = Room::all();
            Log::info('Rooms count:', ['count' => $rooms->count()]);
            
            return view('tenant.admin.room', compact('rooms'));
        } catch (\Exception $e) {
            Log::error('Database error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Database error: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tenant.admin.room.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomRequest $request)
    {
        try {
            // Debug tenant database connection
            $dbName = DB::connection()->getDatabaseName();
            Log::info('Current database:', ['database' => $dbName]);

            // Debug the request data
            Log::info('Store Room Request:', [
                'request_data' => $request->all(),
                'tenant' => tenant()->toArray(),
                'database' => $dbName
            ]);

            // Start a database transaction
            DB::beginTransaction();

            try {
                // Create the room
                $room = new Room([
                    'room_number' => $request->room_number,
                    'capacity' => $request->capacity,
                    'price' => $request->price,
                    'status' => $request->status ?? 'available',
                ]);

                // Log before save
                Log::info('Attempting to save room:', ['room' => $room->toArray()]);

                // Save the room
                $saved = $room->save();

                // Log after save
                Log::info('Room save result:', [
                    'saved' => $saved,
                    'room_id' => $room->id,
                    'room' => $room->toArray()
                ]);

                // Commit the transaction
                DB::commit();

                return redirect()->route('tenant.admin.room')->with('success', 'Room created successfully');
            } catch (\Exception $e) {
                // Rollback the transaction on error
                DB::rollBack();
                Log::error('Database error during room creation:', [
                    'error' => $e->getMessage(),
                    'sql' => $e->getPrevious() ? $e->getPrevious()->getMessage() : null,
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error creating room:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Failed to create room: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $room = Room::findOrFail($id);
        return view('tenant.admin.room.show', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $room = Room::findOrFail($id);
        return view('tenant.admin.room.edit', compact('room'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomRequest $request, string $id)
    {
        $room = Room::findOrFail($id);
        $room->update($request->validated());
        return redirect()->route('tenant.admin.room')->with('success', 'Room updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $room = Room::findOrFail($id);
        $room->delete();
        return redirect()->route('tenant.admin.room')->with('success', 'Room has been deleted');
    }
}

