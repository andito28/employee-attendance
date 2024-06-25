<?php

namespace App\Services\Excel;

use App\Models\Attendance\Timesheet;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\Services\Constant\Attendance\TimesheetStatus;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

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
        return [
            $attendance->employee->name,
            $attendance->shift->name,
            $attendance->clockIn,
            $attendance->clockOut,
            TimesheetStatus::display($attendance->statusId),
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
