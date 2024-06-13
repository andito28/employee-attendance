<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('test:cron')->daily();
Schedule::command('app:set-weekly-day-off')->yearlyOn(1, 1, '01:00');
