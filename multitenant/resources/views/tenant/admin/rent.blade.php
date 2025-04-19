@extends('layouts.admin')

@section('title', 'Rental Collection & Payment Tracking')

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Rental Collection & Payment Tracking</h1>
    </div>

    <!-- Rental List Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Rental Collection & Payment List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="rentalsTable">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Room Number</th>
                            <th>User</th>
                            <th>Price</th>
                            <th>Due Date</th>
                            <th>Payment Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($rentals as $rental)
                        <tr>
                            <td>{{ $rental->id }}</td>
                            <td>{{ $rental->room->room_number ?? 'N/A' }}</td>
                            <td>{{ $rental->user->name ?? 'Unknown' }}</td>
                            <td>₱{{ number_format($rental->price, 2) }}</td>
                            <td>{{ \Carbon\Carbon::parse($rental->due_date)->toFormattedDateString() }}</td>
                            <td>{{ $rental->payment_status }}</td>
                            <td>
                                @if($rental->payment_status !== 'paid')
                                    <form action="{{ route('tenant.admin.rent.markAsPaid', $rental->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-primary" onclick="return confirm('Mark as paid?')">Mark as Paid</button>
                                    </form>
                                @else
                                    <span class="text-muted">No action needed</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Add DataTables CSS -->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<!-- Add DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        console.log("Initializing DataTable...");
        $('#rentalsTable').DataTable({
            paging: true, 
            searching: true,  
            ordering: true,  
            info: true, 
        });
    });
</script>





@endsection
