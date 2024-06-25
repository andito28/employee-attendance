<?php
namespace App\Services\PDF;

use PDF;
use Carbon\Carbon;
use App\Models\Attendance\Timesheet;
use App\Services\Constant\Attendance\TimesheetStatus;
use App\Services\Constant\Attendance\TimesheetCorrectionApproval;

class GenerateTimesheetPdf
{
    public function generate($request)
    {
        $attendanceRecords = Timesheet::FilterYearMonth($request)->orderBy('employeeId')->get();
        $mappedAttendances = $attendanceRecords->map(function($attendance) {

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
                'name' => $attendance->employee->name,
                'shift' => $attendance->shift->name,
                'date' => $attendance->date,
                'clockIn' => $clockIn,
                'clockOut' => $clockOut,
                'status' => TimesheetStatus::display($status)
            ];
        });

        $monthYear = $request->input('date', Carbon::now()->format('m/Y'));
        $date = Carbon::createFromFormat('m/Y', $monthYear);

        $data = [
            'title' => 'Report Timesheet',
            'date' => "Bulan ".$date->format('m').", Tahun ".$date->format('Y'),
            'attendances' =>  $mappedAttendances
        ];

        $pdf = PDF::loadView('pdf.report_timesheet', $data);
        return $pdf->download("timesheet_".$date->format('m').$date->format('Y')."pdf");
    }

}
