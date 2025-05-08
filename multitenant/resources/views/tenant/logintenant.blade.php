<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ tenant()->name }} | Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Tailwind CSS CDN for smooth UI effects -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: linear-gradient(135deg, {{ $theme['primary_color'] ?? '#4f46e5' }}, {{ $theme['secondary_color'] ?? '#9333ea' }});
        }
        .custom-button {
            background-color: {{ $theme['primary_color'] ?? '#4f46e5' }};
        }
        .custom-button:hover {
            background-color: {{ adjustBrightness($theme['primary_color'] ?? '#4f46e5', -20) }};
        }
        .custom-focus:focus {
            --tw-ring-color: {{ $theme['primary_color'] ?? '#4f46e5' }};
        }
        .custom-link {
            color: {{ $theme['primary_color'] ?? '#4f46e5' }};
        }
    </style>
</head>
<body class="font-sans min-h-screen flex flex-col justify-center items-center p-4">
    <div class="bg-white p-8 rounded-xl shadow-xl w-full max-w-md">
        <!-- Logo and Title -->
        <div class="text-center mb-8">
            @if(isset($theme['logo_path']))
                <img src="{{ Storage::url($theme['logo_path']) }}" 
                     alt="{{ tenant()->name }} Logo"
                     class="mx-auto h-16 object-contain mb-4">
            @endif
            <h2 class="text-3xl font-semibold text-gray-800">Welcome Back</h2>
            <p class="text-gray-600 mt-2">Login to {{ tenant()->name }}</p>
        </div>

        <!-- Error Alert -->
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <strong class="font-bold">Oops!</strong>
                <ul class="list-disc pl-5 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('tenant.login') }}" class="space-y-6">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" 
                       id="email"
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       class="w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 custom-focus transition duration-300" 
                       placeholder="Enter your email">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" 
                       id="password"
                       name="password" 
                       required 
                       class="w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 custom-focus transition duration-300" 
                       placeholder="Enter your password">
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="remember" 
                           name="remember" 
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
                </div>
            </div>

            <button type="submit" 
                    class="w-full py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white custom-button focus:outline-none focus:ring-2 focus:ring-offset-2 custom-focus transition duration-300 transform hover:-translate-y-0.5">
                Sign In
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Don't have an account? 
                <a href="{{ route('tenant.register.form') }}" class="font-medium custom-link hover:underline">
                    Register here
                </a>
            </p>
        </div>
    </div>

    <!-- Back to Home Link -->
    <a href="/" class="mt-8 text-white hover:text-gray-200 flex items-center transition duration-300">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Home
    </a>
</body>
</html>

@php
function adjustBrightness($hex, $steps) {
    $hex = str_replace('#', '', $hex);
    
    $r = max(0, min(255, hexdec(substr($hex, 0, 2)) + $steps));
    $g = max(0, min(255, hexdec(substr($hex, 2, 2)) + $steps));
    $b = max(0, min(255, hexdec(substr($hex, 4, 2)) + $steps));
    
    return sprintf("#%02x%02x%02x", $r, $g, $b);
}
@endphp
