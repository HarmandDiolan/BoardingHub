@extends('layouts.user')

@section('title', 'Available Rooms')

@section('user')
<div class="container">
    <h1 class="mb-4">Available Rooms</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Rented Room Info --}}
    @if($rentedRoom)
        <div class="alert alert-info">
            <h5>You have currently rented:</h5>
            <p><strong>Room Number:</strong> {{ $rentedRoom->room_number }}</p>
            <p><strong>Capacity:</strong> {{ $rentedRoom->capacity }}</p>
            <p><strong>Price:</strong> ₱{{ number_format($rentedRoom->price, 2) }}</p>
            @if($rentedRoom->due_date)
                <p><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($rentedRoom->due_date)->toFormattedDateString() }}</p>
            @endif
        </div>
    @endif

    {{-- Available Rooms --}}
    <div class="row">
        @forelse($rooms as $room)
            <div class="col-md-4 mb-4">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title">Room: {{ $room->room_number }}</h5>
                        <p>Capacity: {{ $room->capacity }}</p>
                        <p>Price: ₱{{ number_format($room->price, 2) }}</p>

                        <form method="POST" action="{{ route('tenant.user.rooms.rent', $room->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-success">Rent this Room</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p>No rooms are currently available.</p>
        @endforelse
    </div>
</div>
@endsection
