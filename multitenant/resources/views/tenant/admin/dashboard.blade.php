@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<h1 class="mb-4">Dashboard</h1>

<!-- Add a toast container for notifications -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="reportToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto" id="toastTitle">Report Status</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toastMessage"></div>
    </div>
</div>

<div class="mb-4">
    <div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" type="button" id="reportsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-file-pdf mr-1"></i> Generate Reports
        </button>
        <ul class="dropdown-menu" aria-labelledby="reportsDropdown">
            <li><h6 class="dropdown-header">Module Reports</h6></li>
            <li><a class="dropdown-item" href="#" onclick="downloadReport('/export-pdf/rooms')">
                <i class="fa fa-home mr-2"></i> Rooms Report
            </a></li>
            <li><a class="dropdown-item" href="#" onclick="downloadReport('/export-pdf/rentals')">
                <i class="fa fa-money-bill mr-2"></i> Payments Report
            </a></li>
            <li><a class="dropdown-item" href="#" onclick="downloadReport('/export-pdf/tenants')">
                <i class="fa fa-users mr-2"></i> Users Report
            </a></li>
            <li><hr class="dropdown-divider"></li>
            <li><h6 class="dropdown-header">Other Reports</h6></li>
            <li><a class="dropdown-item" href="#" onclick="downloadReport('/export-pdf/complaints')">
                <i class="fa fa-exclamation-circle mr-2"></i> Complaints Report
            </a></li>
            <li><a class="dropdown-item" href="#" onclick="downloadReport('/export-pdf')">
                <i class="fa fa-file mr-2"></i> General Report
            </a></li>
        </ul>
    </div>
</div>
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

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card draggable-card">
            <div class="card-header bg-primary text-white">
                Users Overview
                <button type="button" class="btn btn-sm btn-light float-right" onclick="toggleCard('usersCard', this)">-</button>
            </div>
            <div class="card-body" id="usersCard">
                <canvas id="usersChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card draggable-card">
            <div class="card-header bg-success text-white">
                Rooms Overview
                <button type="button" class="btn btn-sm btn-light float-right" onclick="toggleCard('roomsCard', this)">-</button>
            </div>
            <div class="card-body" id="roomsCard">
                <canvas id="roomsChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-12 mb-4">
        <div class="card draggable-card">
            <div class="card-header bg-danger text-white">
                Complaints Overview
                <button type="button" class="btn btn-sm btn-light float-right" onclick="toggleCard('complaintsCard', this)">-</button>
            </div>
            <div class="card-body" id="complaintsCard">
                <canvas id="complaintsChart"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    // Show toast notification
    function showToast(title, message, isError = false) {
        const toast = document.getElementById('reportToast');
        const toastTitle = document.getElementById('toastTitle');
        const toastMessage = document.getElementById('toastMessage');
        
        toastTitle.textContent = title;
        toastMessage.textContent = message;
        
        if (isError) {
            toast.classList.add('bg-danger', 'text-white');
        } else {
            toast.classList.remove('bg-danger', 'text-white');
        }
        
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
    }

    // Download report function
    function downloadReport(path) {
        // Show loading indicator
        const button = document.querySelector('#reportsDropdown');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Generating...';
        button.disabled = true;

        fetch(path)
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.error || 'Failed to generate report');
                    });
                }
                return response.blob();
            })
            .then(blob => {
                if (blob.type === 'application/json') {
                    return blob.text().then(text => {
                        const error = JSON.parse(text);
                        throw new Error(error.error || 'Failed to generate report');
                    });
                }
                
                // Create a temporary URL for the blob
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'report.pdf';
                document.body.appendChild(a);
                a.click();
                
                // Show success message
                showToast('Success', 'Report generated successfully');
                
                // Cleanup
                setTimeout(() => {
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(a);
                    button.innerHTML = originalText;
                    button.disabled = false;
                }, 1000);
            })
            .catch(error => {
                console.error('Error downloading report:', error);
                showToast('Error', error.message, true);
                button.innerHTML = originalText;
                button.disabled = false;
            });
    }

    // Initialize charts when the page loads
    window.onload = function () {
        // Initialize the charts
        const usersChart = new Chart(document.getElementById('usersChart'), {
            type: 'bar',
            data: {
                labels: ['Users'],
                datasets: [{
                    label: 'Users by Role',
                    data: [ {{ $regularUsers ?? 0 }}],
                    backgroundColor: ['#007bff', '#28a745', '#ffc107']
                }]
            }
        });

        const roomsChart = new Chart(document.getElementById('roomsChart'), {
            type: 'pie',
            data: {
                labels: ['Occupied', 'Available'],
                datasets: [{
                    label: 'Rooms',
                    data: [{{ $occupiedRooms ?? 8 }}, {{ $availableRooms ?? 12 }}],
                    backgroundColor: ['#28a745', '#ffc107']
                }]
            }
        });

        const complaintsChart = new Chart(document.getElementById('complaintsChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($complaintDates ?? ['Jan', 'Feb', 'Mar']) !!},
                datasets: [{
                    label: 'Complaints',
                    data: {!! json_encode($complaintCounts ?? [3, 6, 2]) !!},
                    borderColor: '#dc3545',
                    fill: false,
                    tension: 0.3
                }]
            }
        });
    };

    // Make cards draggable
    $(function () {
        $(".draggable-card").draggable({
            handle: ".card-header"
        });
    });

    // Toggle card visibility
    function toggleCard(cardId, button) {
        var cardBody = document.getElementById(cardId);
        if (cardBody.style.display === "none") {
            cardBody.style.display = "block";
            button.textContent = "-";
        } else {
            cardBody.style.display = "none";
            button.textContent = "+";
        }
    }
</script>
@endpush

@endsection
