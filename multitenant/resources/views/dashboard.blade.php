<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
            <main class="flex flex-col lg:flex-row w-full max-w-4xl gap-8 justify-center">
                <!-- Form Section -->
                <form action="{{ route('subdomain.store') }}" method="post" class="w-full max-w-md bg-white p-6 rounded-lg shadow-lg">
                    @csrf
                    <div class="mb-4">
                        <label for="subdomain" class="block text-lg font-semibold text-gray-700">Subdomain</label>
                        <div class="flex items-center space-x-3">
                            <input type="text" name="subdomain" id="subdomain" class="p-3 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Enter your subdomain" required>
                            <button type="submit" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">
                                Save
                            </button>
                        </div>
                    </div>
                </form>
            </main>
        </div>
</x-app-layout>
