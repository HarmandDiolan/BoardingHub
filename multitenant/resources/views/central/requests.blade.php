@extends('layouts.dashboardlayout')

@section('title', 'Requests')
@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
    <div class="container py-4">
        <h2 class="mb-3">Subdomain Requests</h2>

        @if($requests->isEmpty())
            <div class="alert alert-warning">No pending requests found.</div>
        @else
            <table id="example" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Domain</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $request)
                        <tr>
                            <td>{{ $request->name }}</td>
                            <td>{{ $request->email }}</td>
                            <td>{{ $request->subdomain . '.localhost' }}</td>
                            <td>{{ ucfirst($request->status) }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    @if($request->status !== 'approved')
                                    <form action="{{ route('subdomain.approve', $request->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" style="border: none; background: transparent;" title="Approve">
                                            <i class="fa fa-check text-success"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <a href="#" title="Plan">
                                        <i class="fa fa-credit-card text-primary"></i>
                                    </a>
                                    <form action="{{ route('subdomain.destroy', $request->subdomain) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="border: none; background: transparent;" title="Reject" onclick="return confirm('Are you sure you want to delete this request?')">
                                            <i class="fa fa-close text-danger"></i>
                                        </button>
                                    </form>
                                        <form action="{{route('subdomain.update', $request->subdomain)}}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" title="{{ $request->tenant?->disabled ? 'Enable' : 'Disable' }}" style="border: none; background: transparent;">
                                                <i class="fa {{ $request->tenant?->disabled ? 'fa-unlock text-success' : 'fa-ban text-danger' }}"></i>
                                            </button>
                                        </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#example').DataTable();
        });
    </script>

@endsection
