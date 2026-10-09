<?php

namespace App\Console\Commands;

use App\Enums\BookingState;
use App\Models\Bookings;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeleteBookingNotVerify extends Command
{
    protected $signature = 'cron:deletebooking';

    protected $description = 'Cancel bookings still unverified two days after their start';

    public function handle(): int
    {
        $stale = Bookings::inState(BookingState::Requested)
            ->whereDate('Booking_start', '<=', now()->subDays(2))
            ->get();
        $stale->each->cancel();

        Log::info('cron:deletebooking cancelled unverified bookings', ['ids' => $stale->modelKeys()]);
        $this->info("cancelled {$stale->count()} unverified bookings");

        return self::SUCCESS;
    }
}
