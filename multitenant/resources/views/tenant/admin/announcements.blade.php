@extends('layouts.admin')

@section('title', 'Complaints')

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

    <div class="container">
    <h2>Create Announcement</h2>
    <form method="POST" action="{{ route('tenant.admin.announcements.store') }}">
        @csrf
        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Content</label>
            <textarea name="content" class="form-control" rows="4" required></textarea>
        </div>
        <div class="form-check mb-3">
            <input type="checkbox" name="is_active" class="form-check-input" checked>
            <label class="form-check-label">Active</label>
        </div>
        <button class="btn btn-primary">Publish</button>
    </form>

    <hr>
    <h3>Existing Announcements</h3>
    <ul class="list-group">
        @foreach($announcements as $a)
            <li class="list-group-item">
                <strong>{{ $a->title }}</strong>
                <p>{{ $a->content }}</p>
                <small>{{ $a->created_at->format('M d, Y') }}</small>
            </li>
        @endforeach
    </ul>
</div>

@endsection
