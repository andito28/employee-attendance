<?php

namespace App\Services\Excel;

use App\Models\Attendance\Attendance;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\Services\Constant\AttendanceStatus;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendancesPerMonthSheet implements FromQuery, WithTitle, WithHeadings, WithMapping
{
    private $month;
    private $year;

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
        return Attendance::query()->with('employee', 'shift')
                ->whereYear('createdAt', $this->year)
                ->whereMonth('createdAt', $this->month);
    }

    public function map($attendance): array
    {
        return [
            $attendance->employee->name,
            $attendance->clockIn,
            $attendance->clockOut,
            $attendance->shift->name,
            AttendanceStatus::display($attendance->statusId),
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'EMPLOYEE',
            'CLOCK IN',
            'CLOCK OUT',
            'SHIFT',
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
