<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Tenant Plan' }}</title>

    {{-- Tailwind CSS via Vite --}}
    @vite('resources/css/app.css')

    {{-- Optional: Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-[#FFFDF9] text-gray-800 font-[Inter]">

<h1 class="text-3xl font-bold text-center text-[#4B2E2B] mb-6">{{ $tenant->subdomain }}</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info">
            {{ session('info') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    
<div class="max-w-2xl mx-auto bg-[#FFF8F0] border border-[#EBD8C3] p-8 rounded-xl shadow-md">
    <h2 class="text-2xl font-bold text-[#5C4033] mb-6">Choose a Plan for <span class="text-[#8B5E3C]">{{ $tenant->subdomain }}</span></h2>

    <form action="{{ route('subdomain.upgrade', $subdomain) }}" method="POST">
        @csrf

        <div class="mb-5">
            <label class="block text-[#5C4033] font-medium mb-2">Select Plan:</label>
            <select name="plan_id" class="w-full px-4 py-2 rounded-lg border border-[#D6BFA7]" required>
                @foreach ($plans as $plan)
                    <option value="{{ $plan->id }}" {{ $tenant->plan_id == $plan->id ? 'selected' : '' }}>
                        {{ $plan->name }} - ₱{{ number_format($plan->price, 2) }} / month
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-5">
            <label class="block text-[#5C4033] font-medium mb-2">Payment Method:</label>
            <select name="payment_method" class="w-full px-4 py-2 rounded-lg border border-[#D6BFA7]" required>
                <option value="gcash">GCash</option>
                <option value="bdo">BDO Bank Transfer</option>
                <option value="debit_card">Debit Card</option>
            </select>
        </div>

        <div class="mb-5">
            <label class="block text-[#5C4033] font-medium mb-2">Reference No / Transaction ID:</label>
            <input type="text" name="payment_reference" required class="w-full px-4 py-2 rounded-lg border border-[#D6BFA7]">
        </div>

        <p class="text-sm text-[#7C4A2D] mb-6">After submitting, our team will verify your payment within 24 hours.</p>

        <button type="submit" class="w-full bg-[#8B5E3C] hover:bg-[#6A4B33] text-white py-3 rounded-lg font-semibold">
            Submit Payment
        </button>
    </form>
</div>

</body>
</html>
