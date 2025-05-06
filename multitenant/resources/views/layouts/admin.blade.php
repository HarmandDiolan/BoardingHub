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
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        /* Default light mode styles */
        body {
            background-color: white;
            color: black;
        }
        /* Dark mode styles */
        .dark body {
            background-color: #121212;
            color: white;
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
        #darkModeToggle {
            color: #ddd;
            padding: 15px;
            display: block;
            text-decoration: none;
            transition: all 0.3s;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
        }
        #darkModeToggle:hover {
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

    <!-- Dark Mode Toggle -->
    <button id="darkModeToggle" class="p-2 bg-gray-200 dark:bg-gray-800 text-gray-800 dark:text-white">
        Toggle Dark Mode
    </button>
    
    <a href="{{ route('tenant.admin.settings') }}" class="{{ request()->routeIs('tenant.admin.settings') ? 'active' : '' }}">
        <i class="fas fa-cogs me-2"></i> Settings
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

<script>
    // Check if dark mode is enabled in localStorage
    if (localStorage.getItem('dark-mode') === 'enabled') {
        document.documentElement.classList.add('dark');
    }

    // Dark mode toggle button
    const darkModeToggle = document.getElementById('darkModeToggle');

    if (darkModeToggle) {
        // Toggle dark mode on button click
        darkModeToggle.addEventListener('click', () => {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('dark-mode', 'disabled');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('dark-mode', 'enabled');
            }
        });
    }
</script>

</body>
</html>
