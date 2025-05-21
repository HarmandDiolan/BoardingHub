<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Open+Sans:wght@300;400;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    @stack('styles')
    <style>
        :root {
            --primary-color: {{ $theme['primary_color'] ?? '#343a40' }};
            --secondary-color: {{ $theme['secondary_color'] ?? '#495057' }};
            --sidebar-color: {{ $theme['sidebar_color'] ?? '#343a40' }};
            --text-color: {{ $theme['text_color'] ?? '#ffffff' }};
            --font-family: {{ $theme['font_family'] ?? 'Segoe UI' }}, sans-serif;
        }

        body {
            font-family: var(--font-family);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Default light mode styles */
        body {
            background-color: #f8f9fa;
            color: #333;
        }

        /* Dark mode styles */
        .dark body {
            background-color: #121212;
            color: var(--text-color);
        }

        .sidebar {
            height: 100vh;
            position: fixed;
            width: 250px;
            top: 0;
            left: 0;
            background-color: var(--sidebar-color);
            padding-top: 56px;
            transition: all 0.3s;
        }

        .sidebar a {
            color: var(--text-color);
            padding: 15px;
            display: block;
            text-decoration: none;
            transition: all 0.3s;
        }

        .sidebar a:hover {
            background-color: var(--secondary-color);
            color: var(--text-color);
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            padding-top: 80px;
            transition: all 0.3s;
        }

        .navbar {
            background-color: var(--primary-color) !important;
            z-index: 1000;
        }

        .navbar-brand, .navbar .text-light {
            color: var(--text-color) !important;
        }

        .sidebar .active {
            background-color: var(--secondary-color);
            color: var(--text-color);
        }

        .logout-btn {
            color: var(--text-color);
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
            background-color: var(--secondary-color);
            color: var(--text-color);
        }

        /* Card styles based on theme settings */
        .card {
            transition: all 0.3s ease;
            background-color: {{ $theme['navbar_style'] === 'dark' ? '#2c3034' : '#ffffff' }};
            color: {{ $theme['navbar_style'] === 'dark' ? '#ffffff' : '#333333' }};
        }

        @if($theme['card_style'] === 'flat')
        .card {
            border: none;
            box-shadow: none;
        }
        @elseif($theme['card_style'] === 'shadow')
        .card {
            border: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        @endif

        /* Button styles */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: var(--text-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
    </style>
</head>
<body>

<!-- Topbar -->
<nav class="navbar navbar-expand fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('tenant.admin.dashboard') }}">
            @if(isset($theme['logo_path']))
                <img src="{{ Storage::url($theme['logo_path']) }}" 
                     alt="Logo" 
                     class="me-2"
                     style="max-height: 40px;">
            @endif
            <span>Admin Dashboard</span>
        </a>
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
    <a href="{{ route('tenant.admin.settings') }}" class="{{ request()->routeIs('tenant.admin.settings') ? 'active' : '' }}">
        <i class="fas fa-cogs me-2"></i> Settings
    </a>
    <a href="{{ route('tenant.admin.theme') }}" class="{{ request()->routeIs('tenant.admin.theme') ? 'active' : '' }}">
        <i class="fas fa-paint-brush me-2"></i> Theme Settings
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
