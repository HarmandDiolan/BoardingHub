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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
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
            handle: ".card-header"  // Make only the header part draggable
        });
    });

    // Toggle card visibility (minimize and restore)
    function toggleCard(cardId, button) {
        var cardBody = document.getElementById(cardId);
        
        // Toggle visibility of the card body
        if (cardBody.style.display === "none") {
            cardBody.style.display = "block";
            button.textContent = "-";  // Change button text to "-"
        } else {
            cardBody.style.display = "none";
            button.textContent = "+";  // Change button text to "+"
        }
    }
</script>

@endsection
