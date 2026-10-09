<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('cron:deletebooking')->daily();
