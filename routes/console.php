<?php

use App\Models\Bookings;
use Illuminate\Support\Facades\Schedule;

Schedule::call(fn () => Bookings::expireDue())->everyFiveMinutes()->name('bookings:expire');
Schedule::command('cron:deletebooking')->daily();
