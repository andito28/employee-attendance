<?php

namespace App\Jobs\Employee;

use DB;
use Log;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use App\Models\Attendance\Schedule;
use Illuminate\Queue\SerializesModels;
use App\Models\Attendance\PublicHoliday;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Constant\Attendance\ScheduleType;
use App\Services\Constant\Attendance\WeeklyDayOffConstant;

class CreateSchedulesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $employeeId;
    protected $createdBy;

    public function __construct($employeeId, $createdBy)
    {
        $this->employeeId = $employeeId;
        $this->createdBy = $createdBy;
    }
    public function handle(): void
    {
        DB::transaction(function () {
            $this->createWeeklyDayOffSchedules();
            $this->createPublicHolidaySchedules();
        });
    }

    protected function createWeeklyDayOffSchedules(): void
    {
        $dateNow = Carbon::today();
        $weeklyDayOffDate = Carbon::create(Carbon::now()->year,
        WeeklyDayOffConstant::MONTH, WeeklyDayOffConstant::DAY);

        if ($dateNow->greaterThanOrEqualTo($weeklyDayOffDate)) {
            $firstSundayAfterToday = $dateNow->copy()->next(Carbon::SUNDAY);
            $endDateWeeklyDayOff = $weeklyDayOffDate->copy()->addYear();
            for ($date = $firstSundayAfterToday; $date->lessThanOrEqualTo($endDateWeeklyDayOff); $date->addWeek()) {
                $this->createSchedule($date);
            }
        }else{
            $date = $dateNow->copy()->next(Carbon::SUNDAY);
            if (!$date->equalTo($weeklyDayOffDate)) {
                while ($date->lessThanOrEqualTo($weeklyDayOffDate)) {
                    $this->createSchedule($date);
                    $date->addWeek();
                }
            }
        }
    }

    protected function createPublicHolidaySchedules(): void
    {
        $publicHolidays = PublicHoliday::whereYear('date', Carbon::now()->year)->get();
        foreach ($publicHolidays as $holiday) {
            $holidayDate = Carbon::parse($holiday->date);
            $this->createSchedule($holidayDate, ScheduleType::PUBLIC_HOLIDAY_ID,$holiday->id,PublicHoliday::class);
        }
    }

    protected function createSchedule($date, $typeId = ScheduleType::WEEKLY_DAY_OFF_ID,$reference = NULL,$referenceType = NULL): void
    {
        $date = Carbon::parse($date);
        Schedule::create([
            'employeeId' => $this->employeeId,
            'date' => $date->format('Y-m-d'),
            'typeId' => $typeId,
            'reference' => $reference,
            'referenceType' => $referenceType,
            'createdBy' => $this->createdBy['createdBy'],
            'createdByName' => $this->createdBy['createdByName'],
        ]);
    }
}
