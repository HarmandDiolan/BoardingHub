@component('mail::message')
# Hello {{ $rental->user->name }},

This is a reminder that your rent for **Room {{ $rental->room->room_number }}** is due on **{{ \Carbon\Carbon::parse($rental->due_date)->format('F j, Y') }}**.

**Amount Due:** ₱{{ number_format($rental->price, 2) }}

@component('mail::button', ['url' => url('/tenant/user/rooms')])
View Your Room & Payments
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
