@extends('layouts.admin')

@section('title', 'Room Occupant')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Room Occupant Details</h6>
        </div>
        <div class="card-body">
            <h5 class="card-title">Room Number: {{ $room->room_number }}</h5>
            <p><strong>Capacity:</strong> {{ $room->capacity }}</p>
            <p><strong>Price:</strong> ₱{{ number_format($room->price, 2) }}</p>
            <p><strong>Status:</strong> 
                <span class="badge bg-{{ $room->status === 'available' ? 'success' : 'danger' }}">
                    {{ ucfirst($room->status) }}
                </span>
            </p>

            @if($occupant)
                <h5 class="mt-4">Occupant Details</h5>
                <p><strong>Name:</strong> {{ $occupant->name }}</p>
                <p><strong>Email:</strong> {{ $occupant->email }}</p>
                <!-- Add more occupant details as needed -->
            @else
                <p>No occupant for this room.</p>
            @endif
        </div>
    </div>
</div>
@endsection
