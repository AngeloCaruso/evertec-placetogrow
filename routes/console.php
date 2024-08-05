<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('payments:clear-expired')->everyMinute();
