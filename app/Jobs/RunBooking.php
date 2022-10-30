<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Bookings;
use Illuminate\Support\Facades\Log;

class RunBooking implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

	private $data;
    public function __construct(Bookings $Booking)
    {
        $this->data = $Booking;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
	   Log::info('success');	
       $this->data->delete();
    }
}
