
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
        }
        .sidebar {
            height: 100vh;
            position: fixed;
            width: 250px;
            top: 0;
            left: 0;
            background-color: #343a40;
            padding-top: 56px; /* navbar height */
        }
        .sidebar a {
            color: #ddd;
            padding: 15px;
            display: block;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            padding-top: 80px;
        }
        .navbar {
            z-index: 1000;
        }
    </style>
</head>
<body>

<!-- Topbar -->
<nav class="navbar navbar-expand navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Admin </a>
    </div>
</nav>

<!-- Sidebar -->
<div class="sidebar">
    <a href="#"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
    <a href="#"><i class="fas fa-columns me-2"></i> Layouts</a>
    <a href="#"><i class="fas fa-file-alt me-2"></i>Logout</a>
    <div class="text-light small text-center mt-4">Logged in as: Admin<br><strong> {{ $user->name }}</strong></div>
</div>

<!-- Page Content -->
<div class="content">
    <h1 class="mb-4">Dashboard</h1>

    <div class="row">
        <div class="col-md-3 mb-3">
            <a href="{{ route('rooms.index') }}" style="text-decoration: none;">
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

    <div class="card mt-4">
        <div class="card-body">
            
        </div>
    </div>
</div>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @yield('admin')
</body>
</html>
