<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @stack('styles')
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
            padding-top: 56px;
            transition: all 0.3s;
        }
        .sidebar a {
            color: #ddd;
            padding: 15px;
            display: block;
            text-decoration: none;
            transition: all 0.3s;
        }
        .sidebar a:hover {
            background-color: #495057;
            color: #fff;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            padding-top: 80px;
            transition: all 0.3s;
        }
        .navbar {
            z-index: 1000;
        }
        .sidebar .active {
            background-color: #495057;
            color: #fff;
        }
        .logout-btn {
            color: #ddd;
            padding: 15px;
            display: block;
            text-decoration: none;
            transition: all 0.3s;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }
        .logout-btn:hover {
            background-color: #495057;
            color: #fff;
        }
    </style>
</head>
<body>

<!-- Topbar -->
<nav class="navbar navbar-expand navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('tenant.admin.dashboard') }}">Admin Dashboard</a>
        <div class="d-flex align-items-center">
            <span class="text-light me-3">Welcome, {{ Auth::user()->name }}</span>
        </div>
    </div>
</nav>

<!-- Sidebar -->
<div class="sidebar">
    <a href="{{ route('tenant.admin.dashboard') }}" class="{{ request()->routeIs('tenant.admin.dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
    </a>
    <a href="{{ route('tenant.admin.room') }}" class="{{ request()->routeIs('tenant.admin.room*') ? 'active' : '' }}">
        <i class="fas fa-door-open me-2"></i> Rooms
    </a>
    <a href="{{ route('tenant.admin.complaints.index') }}" class="{{ request()->routeIs('tenant.admin.complaints.index*') ? 'active' : '' }}">
        <i class="fas fa-comments me-2"></i> Complaint
    </a>

    <a href="{{ route('tenant.admin.rent.rentalIndex') }}" class="{{ request()->routeIs('tenant.admin.rent.rentalIndex*') ? 'active' : '' }}">
        <i class="fa-solid fa-money-bill"></i> Payment
    </a>

    <a href="{{ route('tenant.admin.users') }}" class="{{ request()->routeIs('tenant.admin.users*') ? 'active' : '' }}">
        <i class="fa-solid fa-user"></i> User
    </a>

    <a href="{{ route('tenant.admin.announcements') }}" class="{{ request()->routeIs('tenant.admin.announcements*') ? 'active' : '' }}">
        <i class="fa fa-bullhorn" aria-hidden="true"></i> Announcement
    </a>

    
    <form method="POST" action="{{ route('tenant.logout') }}" class="mt-auto">
        @csrf
        <button type="submit" class="logout-btn">
            <i class="fas fa-sign-out-alt me-2"></i> Logout
        </button>
    </form>
</div>

<!-- Page Content -->
<div class="content">
    @yield('content')
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')

</body>
</html>
