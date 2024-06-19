<?php
namespace App\Services\Excel;

use App\Models\Attendance\Attendance;
use Maatwebsite\Excel\Concerns\FromQuery;
use App\Services\Constant\AttendanceStatus;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GenerateTimesheetExcel implements FromQuery, WithMapping, WithHeadings
{
    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function query()
    {
        return Attendance::query()->with('employee', 'shift');
    }

    /**
     * @param mixed $attendance
     * @return array
     */
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
}
