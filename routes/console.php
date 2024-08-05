<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schedule;

Schedule::command('payments:clear-expired')->everyMinute();
