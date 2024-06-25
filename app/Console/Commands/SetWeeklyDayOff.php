<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\Employee\Employee;
use Illuminate\Support\Facades\DB;
use App\Models\Attendance\Schedule;
use App\Services\Constant\Attendance\ScheduleType;


class SetWeeklyDayOff extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:set-weekly-day-off';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::transaction(function (){
            $employees = Employee::all();
            $startDate = Carbon::today();
            $weeklyDayOffDate = Carbon::create(null, 6, 17);
            $endDate = $startDate->copy()->addYear();

            if ($startDate->isSameDay($weeklyDayOffDate )) {

                while ($startDate->lessThanOrEqualTo($endDate)) {

                    $weeklyDayOff = $startDate->copy()->next(Carbon::SUNDAY);

                    if ($weeklyDayOff->greaterThan($endDate)) {
                        break;
                    }

                    foreach ($employees as $employee) {
                        Schedule::create([
                            'employeeId' => $employee->id,
                            'date' => $weeklyDayOff,
                            'typeId' => ScheduleType::WEEKLY_DAY_OFF_ID,
                            'createdBy' => 'system',
                            'createdByName' => 'system'
                        ]);
                    }
                    $startDate->addWeek();
                }
                return 0;
            }
        });
    }
}
