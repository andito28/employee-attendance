<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('test:cron')->everyMinute();
Schedule::command('app:set-weekly-day-off')->everyMinute();
