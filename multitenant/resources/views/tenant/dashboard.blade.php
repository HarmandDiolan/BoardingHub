<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ tenant()->id }} - BoardingHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: #f8f9fa;
        }
        .hero-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4rem 2rem;
            background: linear-gradient(135deg, {{ $theme['primary_color'] ?? '#343a40' }}, {{ $theme['secondary_color'] ?? '#495057' }});
            color: {{ $theme['text_color'] ?? '#ffffff' }};
        }
        .logo-container {
            max-width: 300px;
            margin-bottom: 2rem;
        }
        .logo-container img {
            max-width: 100%;
            height: auto;
        }
        .tenant-name {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .login-btn {
            font-size: 1.2rem;
            padding: 0.75rem 2.5rem;
            border-radius: 50px;
            background-color: {{ $theme['primary_color'] ?? '#343a40' }};
            border: 2px solid {{ $theme['text_color'] ?? '#ffffff' }};
            color: {{ $theme['text_color'] ?? '#ffffff' }};
            transition: all 0.3s ease;
        }
        .login-btn:hover {
            background-color: {{ $theme['text_color'] ?? '#ffffff' }};
            color: {{ $theme['primary_color'] ?? '#343a40' }};
        }
        .footer {
            padding: 1rem;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="hero-section">
        <div class="container text-center">
            @if(isset($theme['logo_path']))
                <div class="logo-container mx-auto">
                    <img src="{{ Storage::url($theme['logo_path']) }}" 
                         alt="{{ tenant()->name }} Logo"
                         class="img-fluid">
                </div>
            @endif
            
            <h1 class="tenant-name">{{ tenant()->name }}</h1>
            
            <div class="description mb-4">
                <p class="lead">Welcome to our boarding house management system</p>
            </div>

            <a href="{{ route('tenant.login') }}" class="btn login-btn">
                Login
            </a>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} {{ tenant()->name }}. Powered by BoardingHub</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 