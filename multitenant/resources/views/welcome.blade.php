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
    <body class="bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-900 text-gray-800 dark:text-gray-200 flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
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

        <div class="flex flex-col items-center justify-center overflow-auto min-h-screen">
            <div class="text-center mb-8">
                <h1 class="text-5xl font-bold mb-4">
                    <span class="text-gray-800 dark:text-white">Boarding</span>
                    <span class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-3 py-1 rounded-lg shadow-lg">Hub</span>
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-300">Welcome to BoardingHub Tenant Registration</p>
            </div>
            
            <main class="flex flex-col lg:flex-row w-full max-w-4xl gap-8 justify-center">
                <!-- Form Section -->
                <form action="{{ route('subdomain.store') }}" method="post" class="w-full max-w-md bg-white dark:bg-gray-800 p-8 rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] dark:shadow-[0_8px_30px_rgb(255,255,255,0.1)] backdrop-blur-sm">
                    @csrf
                    <h2 class="text-3xl font-semibold text-center text-gray-800 dark:text-white mb-8">Create Your Tenant Account</h2>
                    <div class="mb-6">
                        <label for="name" class="block text-lg font-semibold text-gray-700 dark:text-gray-300">Full Name</label>
                        <input type="text" name="name" id="name" class="p-3 w-full border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:text-white" placeholder="Your Full Name" required>
                    </div>
                    <div class="mb-6">
                        <label for="email" class="block text-lg font-semibold text-gray-700 dark:text-gray-300">Email Address</label>
                        <input type="email" name="email" id="email" class="p-3 w-full border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:text-white" placeholder="Your Email Address" required>
                    </div>
                    <div class="mb-6">
                        <label for="subdomain" class="block text-lg font-semibold text-gray-700 dark:text-gray-300">Subdomain</label>
                        <input type="text" name="subdomain" id="subdomain" class="p-3 w-full border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:text-white" placeholder="Enter Your Subdomain" required>
                    </div>
                    <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-3 rounded-lg hover:shadow-lg transition duration-300 ease-in-out transform hover:-translate-y-0.5">Save</button>
                </form>
            </main>
        </div>

        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
    </body>
</html>
