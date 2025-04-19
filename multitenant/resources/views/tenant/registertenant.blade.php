<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} | Tenant Register</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body {
            background: linear-gradient(to right, #667eea, #764ba2);
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white shadow-2xl rounded-xl p-10 w-full max-w-md animate-fade-in">
            <div class="text-center mb-6">
                <h2 class="text-3xl font-bold text-indigo-600">Tenant Registration</h2>
                <p class="text-gray-500 text-sm mt-1">Create your account</p>
            </div>

            <form method="POST" action="{{ route('tenant.register') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 mb-1" for="email">Name</label>
                    <input type="text" id="name" name="name" placeholder="Full Name" required
                    class="w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-1" for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="you@example.com" required
                    class="w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-1" for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="********" required
                    class="w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 mb-1" for="password_confirmation">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="********" required
                    class="w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300">
                </div>

                <button type="submit"
                        class="w-full py-3 text-white bg-indigo-600 hover:bg-indigo-700 rounded-md text-lg font-semibold transition duration-300 ease-in-out transform hover:-translate-y-0.5 hover:shadow-lg">
                    Register
                </button>

                <p class="mt-4 text-sm text-center text-gray-600">
                    Already have an account? <a href="{{ route('tenant.login') }}" class="text-indigo-500 hover:underline">Login</a>
                </p>
            </form>
        </div>
    </div>

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fade-in 0.6s ease-out;
        }
    </style>
</body>
</html>
