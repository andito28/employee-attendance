<?php
namespace App\Services\PDF;

use PDF;
use Carbon\Carbon;
use App\Models\Attendance\Timesheet;
use App\Services\Constant\Attendance\TimesheetStatus;

class GenerateTimesheetPdf
{
    public function generate($request)
    {
        $attendanceRecords = Timesheet::FilterYearMonth($request)->orderBy('employeeId')->get();
        $mappedAttendances = $attendanceRecords->map(function($attendance) {
            return [
                'name' => $attendance->employee->name,
                'shift' => $attendance->shift->name,
                'clockIn' => $attendance->clockIn,
                'clockOut' => $attendance->clockOut,
                'status' => TimesheetStatus::display($attendance->statusId)
            ];
        });

        $monthYear = $request->input('date', Carbon::now()->format('m/Y'));
        $date = Carbon::createFromFormat('m/Y', $monthYear);

        $data = [
            'title' => 'Report Timesheet',
            'date' => "Bulan ".$date->format('m').", Tahun ".$date->format('Y'),
            'attendances' =>  $mappedAttendances
        ];

        $pdf = PDF::loadView('pdf.reportTimesheet', $data);
        return $pdf->download("timesheet_".$date->format('m').$date->format('Y')."pdf");
    }

}
