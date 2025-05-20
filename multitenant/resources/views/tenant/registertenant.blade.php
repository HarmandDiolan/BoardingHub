<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ tenant()->id }} | Register</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
    <div class="bg-white shadow-2xl rounded-xl p-8 w-full max-w-md">
        <!-- Logo and Title -->
        <div class="text-center mb-8">
            @if(isset($theme['logo_path']))
                <img src="{{ Storage::url($theme['logo_path']) }}" 
                     alt="{{ tenant()->name }} Logo"
                     class="mx-auto h-16 object-contain mb-4">
            @endif
            <h2 class="text-3xl font-semibold text-gray-800">Create Account</h2>
            <p class="text-gray-600 mt-2">Join {{ tenant()->name }}</p>
        </div>

        <!-- Error Alert -->
        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <strong class="font-bold">Oops!</strong>
                <ul class="list-disc pl-5 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('tenant.register') }}" class="space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}" 
                       required
                       class="w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 custom-focus transition duration-300"
                       placeholder="Enter your full name">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required
                       class="w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 custom-focus transition duration-300"
                       placeholder="you@example.com">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       required
                       class="w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 custom-focus transition duration-300"
                       placeholder="Create a strong password">
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <input type="password" 
                       id="password_confirmation" 
                       name="password_confirmation" 
                       required
                       class="w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 custom-focus transition duration-300"
                       placeholder="Confirm your password">
            </div>

            <button type="submit" 
                    class="w-full py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white custom-button focus:outline-none focus:ring-2 focus:ring-offset-2 custom-focus transition duration-300 transform hover:-translate-y-0.5">
                Create Account
            </button>

            <p class="text-center text-sm text-gray-600">
                Already have an account? 
                <a href="{{ route('tenant.login') }}" class="font-medium custom-link hover:underline">
                    Sign in here
                </a>
            </p>
        </form>
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
