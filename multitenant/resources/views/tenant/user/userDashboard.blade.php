@extends('layouts.user')

@section('title', 'Dashboard')

@section('user')

    @if($announcements->isNotEmpty())
        <div class="card mb-4">
            <div class="card-header">
                <h5>Latest Announcements</h5>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @foreach($announcements as $announcement)
                        <li class="list-group-item">
                            <h6>{{ $announcement->title }}</h6>
                            <p>{{ $announcement->content }}</p>
                            <small class="text-muted">{{ $announcement->created_at->diffForHumans() }}</small>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @else
        <div class="alert alert-info">No active announcements available.</div>
    @endif


@endsection