@extends('layouts.admin') <!-- Assuming your layout is in 'layouts.app' -->

@section('title', 'Settings')

@section('content')
    <div class="container">
        <h1>Settings</h1>
        
        <div class="card">
            <div class="card-header">
                <h5>System Update</h5>
            </div>
            <div class="card-body">
                <button id="checkUpdate" class="btn btn-primary">
                    <i class="fas fa-sync-alt me-1"></i> Check for Updates
                </button>
                <button id="updateBtn" class="btn btn-success mt-3" style="display: none;">
                    <i class="fas fa-download me-1"></i> Update Now
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Check for updates and show buttons accordingly
        document.getElementById('checkUpdate').addEventListener('click', function() {
            fetch('/check-update')
                .then(response => response.json())
                .then(data => {
                    if (data.update_available) {
                        alert(`Update available! Latest version: ${data.latest_version}`);
                        document.getElementById('updateBtn').style.display = 'inline-block';
                    } else {
                        alert('You are up to date!');
                    }
                })
                .catch(error => {
                    console.error('Error checking for updates:', error);
                    alert('There was an error checking for updates.');
                });
        });

        // Trigger the update process
        document.getElementById('updateBtn').addEventListener('click', function() {
            fetch('/update-system')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('System updated successfully!');
                    } else {
                        alert('There was an error updating the system.');
                    }
                })
                .catch(error => {
                    console.error('Error updating the system:', error);
                    alert('There was an error updating the system.');
                });
        });
    </script>
@endpush
