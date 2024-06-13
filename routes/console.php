<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('test:cron')->daily();
Schedule::command('app:set-weekly-day-off')->yearlyOn(6, 17, '01:00');
