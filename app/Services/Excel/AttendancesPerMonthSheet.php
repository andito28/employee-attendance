<?php

namespace App\Services\Excel;

use Carbon\Carbon;
use App\Models\Attendance\Timesheet;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Services\Constant\Attendance\TimesheetStatus;
use App\Services\Constant\Attendance\TimesheetCorrectionApproval;

class AttendancesPerMonthSheet implements FromQuery, WithTitle, WithHeadings, WithMapping
{
    private $month;
    private $year;
    private $processedEmployees = [];

    public function __construct(int $year, int $month)
    {
        $this->month = $month;
        $this->year  = $year;
    }

    /**
     * @return Builder
     */
    public function query()
    {
        return Timesheet::generateExcel($this->year, $this->month);
    }

    public function map($attendance): array
    {
        $correction = $attendance->correction();
        $clockIn = Carbon::parse($attendance->clockIn)->format('H:i:s');
        $clockOut = Carbon::parse($attendance->clockOut)->format('H:i:s');
        $status = $attendance->statusId;

        if ($correction && $correction->approvalId == TimesheetCorrectionApproval::APPROVED_ID) {
            $clockIn = $correction->clockIn;
            $clockOut = $correction->clockOut;
            $status = $correction->statusId;
        }

        return [
            $attendance->employee->name,
            $attendance->shift->name,
            $attendance->date,
            $clockIn,
            $clockOut,
            TimesheetStatus::display($status)
        ];
    }


    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'EMPLOYEE',
            'SHIFT',
            'DATE',
            'CLOCK IN',
            'CLOCK OUT',
            'STATUS',
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Month ' . $this->month;
    }
}
