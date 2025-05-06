<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>BoardingHub | Tenant Registration</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
        @endif
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
            
        </header>


        <div class="flex flex-col overflow-auto min-h-screen">
            <h1 class="text-4xl font-bold text-white">
                    Boarding<span class="bg-orange-500 text-white px-2 py-1 rounded">Hub</span>
                </h1>
                <p class="text-lg text-gray-700 mt-2">Welcome to BoardingHub Tenant Registration</p>
            
            <main class="flex flex-col lg:flex-row w-full max-w-4xl gap-8 justify-center">
                <!-- Form Section -->
                <form action="{{ route('subdomain.store') }}" method="post" class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg">
                    @csrf
                    <h2 class="text-3xl font-semibold text-center text-gray-800 mb-8">Create Your Tenant Account</h2>
                    <div class="mb-6">
                        <label for="name" class="block text-lg font-semibold text-gray-700">Full Name</label>
                        <input type="text" name="name" id="name" class="p-3 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Your Full Name" required>
                    </div>
                    <div class="mb-6">
                        <label for="email" class="block text-lg font-semibold text-gray-700">Email Address</label>
                        <input type="email" name="email" id="email" class="p-3 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Your Email Address" required>
                    </div>
                    <div class="mb-6">
                        <label for="subdomain" class="block text-lg font-semibold text-gray-700">Subdomain</label>
                        <input type="text" name="subdomain" id="subdomain" class="p-3 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Enter Your Subdomain" required>
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-md hover:bg-indigo-700 transition duration-300 ease-in-out">Save</button>
                </form>
            </main>
        </div>

        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
    </body>
</html>
