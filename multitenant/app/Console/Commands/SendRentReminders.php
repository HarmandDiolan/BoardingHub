<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant\RoomRental;
use App\Mail\RentReminderMail;
use Illuminate\Support\Facades\Mail;
use Stancl\Tenancy\Database\Models\Tenant;

class SendRentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rent:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send rent reminders to users whose due date is 7 days away.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Checking for upcoming due rents...");

        // Retrieve all tenants
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            // Switch tenant context
            tenancy()->initialize($tenant);

            $this->info("Checking for upcoming due rents for tenant: {$tenant->id}");

            // Get rentals that are due in 7 days
            $rentals = RoomRental::with(['user', 'room'])
                ->whereDate('due_date', now()->addWeek()->toDateString())
                ->where('payment_status', 'unpaid')
                ->get();

            foreach ($rentals as $rental) {
                if ($rental->user && $rental->room) {
                    // Send reminder email
                    Mail::to($rental->user->email)->send(new RentReminderMail($rental));
                    $this->info("✅ Reminder sent to {$rental->user->name}");
                }
            }
        }

        $this->info("Done.");
    }
}
