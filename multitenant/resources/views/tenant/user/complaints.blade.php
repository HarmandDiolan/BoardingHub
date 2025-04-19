@extends('layouts.user')

@section('user')
<div class="container">
    <h2>Submit a Complaint</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('tenant.user.complaints.store') }}">
        @csrf
        <div class="mb-3">
            <label>Subject</label>
            <input type="text" name="subject" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Message</label>
            <textarea name="message" class="form-control" rows="5" required></textarea>
        </div>
        <button class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
