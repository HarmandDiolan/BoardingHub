@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<h1 class="mb-4">Dashboard</h1>

<div class="row">
    <div class="col-md-3 mb-3">
        <a href="{{ route('tenant.admin.room') }}" style="text-decoration: none;">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Room</h5>
                    <p class="card-text">View Details</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">RAAAH</h5>
                <p class="card-text">View Details</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Success Card</h5>
                <p class="card-text">View Details</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <h5 class="card-title">Danger Card</h5>
                <p class="card-text">View Details</p>
            </div>
        </div>
    </div>
</div>
@endsection
