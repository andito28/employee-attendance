<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

use Carbon\Carbon;
use App\Models\Employee\Employee;
use Illuminate\Support\Facades\DB;
use App\Models\Employee\Resignation;
use App\Services\Constant\Employee\StatusEmployee;

use App\Models\Attendance\Schedule;
use App\Services\Constant\Attendance\ScheduleType;
use App\Services\Constant\Attendance\WeeklyDayOffConstant;

class TestCommand extends Command
{
    protected $signature = 'dev-test';
    protected $description = '';

    public function handle()
    {
        //TEST UPDATE RESIGNATION
        // DB::transaction(function (){
        //     $today = Carbon::today();
        //     $resignations = Resignation::whereDate('date', $today)->get();

        //     foreach($resignations as $resign){
        //         $employee = Employee::find($resign->employeeId);
        //         if ($employee) {
        //             $employee->statusId = StatusEmployee::RESIGNED_ID;
        //             $employee->save();
        //         }
        //     }
        // });


        //TEST SET WEEKLY DAY OFF
        DB::transaction(function (){
            $employees = Employee::all();
            $startDate = Carbon::today();
            $weeklyDayOffDate = Carbon::create(null,WeeklyDayOffConstant::MONTH,
            WeeklyDayOffConstant::DAY);
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
