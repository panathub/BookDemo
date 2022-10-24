<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Bookings;
use Carbon\Carbon;

class DeleteBookingNotVerify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:deletebooking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
		$today = Carbon::now()->subDay(2)->endOfDay();
		$bookings = Bookings::whereDate('Booking_start','<=',$today)->get();
		foreach($bookings as $booking) {
			\Log::info('id '. $booking->BookingID. ' start '. $booking->Booking_start. ' end '. $booking->Booking_end. 'is auto deleted');
			$booking->update(['VerifyStatus' => 2]);
			$booking->delete();
		}
    }
}
