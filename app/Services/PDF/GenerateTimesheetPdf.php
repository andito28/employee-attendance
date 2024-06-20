<?php
namespace App\Services\PDF;

use PDF;
use App\Models\Attendance\Attendance;
use App\Services\Constant\AttendanceStatus;

class GenerateTimesheetPdf
{
    public function generate($request)
    {
        $attendanceRecords = Attendance::FilterYearMonth($request)->get();

        $mappedAttendances = $attendanceRecords->map(function($attendance) {
            return [
                'employeeId' => $attendance->employeeId,
                'name' => $attendance->employee->name,
                'shift' => $attendance->shift->name,
                'clockIn' => $attendance->clockIn,
                'clockOut' => $attendance->clockOut,
                'status' => AttendanceStatus::display($attendance->statusId)
            ];
        });

        $groupedAttendances = $mappedAttendances->groupBy('employeeId');

        $data = [
            'title' => 'Report Timesheet',
            'date' => "Bulan ".$request->month.", Tahun ".$request->year,
            'attendances' =>  $groupedAttendances
        ];

        $pdf = PDF::loadView('pdf.reportTimesheet', $data);
        return $pdf->download("timesheet_".$request->year.$request->month."pdf");
    }

}
