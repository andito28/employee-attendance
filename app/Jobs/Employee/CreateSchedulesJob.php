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

class CreateSchedulesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $employeeId;
    protected $createdBy;

    /**
     * Create a new job instance.
     */
    public function __construct($employeeId, $createdBy)
    {
        $this->employeeId = $employeeId;
        $this->createdBy = $createdBy;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::transaction(function () {
            $this->createWeeklyDayOffSchedules();
            $this->createPublicHolidaySchedules();
        });
    }

    /**
     * Create weekly day off schedules.
     */
    protected function createWeeklyDayOffSchedules(): void
    {
        $startDate = Carbon::today();
        $weeklyDayOffDate = Carbon::create(Carbon::now()->year, 6, 17, 1, 0, 0);
        $endDate = $startDate->copy()->addYear();

        if ($startDate->greaterThanOrEqualTo($weeklyDayOffDate) && $startDate->hour > 1) {
            while ($startDate->lessThanOrEqualTo($endDate)) {
                $weeklyDayOff = $startDate->copy()->next(Carbon::SUNDAY);
                if ($weeklyDayOff->greaterThan($endDate)) {
                    break;
                }
                $this->createSchedule($weeklyDayOff);
                $startDate->addWeek();
            }
        } else {
            $currentDate = Carbon::now();
            $firstSundayAfterToday = $currentDate->copy()->next(Carbon::SUNDAY);
            $endDateeeklyDayOffDate = $weeklyDayOffDate->copy()->addYear();
            for ($date = $firstSundayAfterToday; $date->lessThanOrEqualTo($endDateeeklyDayOffDate); $date->addWeek()) {
                $this->createSchedule($date);
            }
        }
    }

    /**
     * Create public holiday schedules.
     */
    protected function createPublicHolidaySchedules(): void
    {
        $publicHolidays = PublicHoliday::whereYear('date', Carbon::now()->year)->get();
        foreach ($publicHolidays as $holiday) {
            $holidayDate = Carbon::parse($holiday->date);
            $this->createSchedule($holidayDate, ScheduleType::PUBLIC_HOLIDAY_ID,$holiday->id,PublicHoliday::class);
        }
    }

    /**
     * Helper function to create a schedule.
     */
    protected function createSchedule($date, $typeId = ScheduleType::WEEKLY_DAY_OFF_ID,$reference = NULL,$referenceType = NULL): void
    {
        $date = Carbon::parse($date); // Ensure $date is a Carbon instance
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
