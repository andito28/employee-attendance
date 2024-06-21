<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\Employee\Employee;
use App\Models\Attendance\Schedule;
use Illuminate\Queue\SerializesModels;
use App\Services\Constant\Attendance\ScheduleType;
use App\Models\Attendance\PublicHoliday;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AssignScheduleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $publicHoliday;
    protected $createdBy;
    /**
     * Create a new job instance.
     */
    public function __construct($publicHoliday,$createdBy)
    {
        $this->publicHoliday = $publicHoliday;
        $this->createdBy = $createdBy;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $employees = Employee::all();
        foreach ($employees as $employee) {

            $existingSchedule = Schedule::where('employeeId', $employee->id)
            ->where('date', $this->publicHoliday->date)
            ->exists();

            if (!$existingSchedule) {
                $data = [
                    'employeeId' => $employee->id,
                    'reference' => $this->publicHoliday->id,
                    'referenceType' => PublicHoliday::class,
                    'typeId' => ScheduleType::PUBLIC_HOLIDAY_ID,
                    'date' => $this->publicHoliday->date,
                ];
                Schedule::create($data + $this->createdBy);
            }
        }

    }
}
