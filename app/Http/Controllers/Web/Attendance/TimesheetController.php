<?php

namespace App\Http\Controllers\Web\Attendance;

use Illuminate\Http\Request;
use App\Mail\TimesheetExportMail;
use App\Http\Controllers\Controller;
use App\Models\Attendance\Timesheet;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\SendEmailTimesheetExcelJob;
use App\Parser\Attendance\TimesheetParser;
use App\Services\PDF\GenerateTimesheetPdf;
use App\Algorithms\Attendance\TimesheetAlgo;
use App\Services\Excel\GenerateTimesheetExcel;


class TimesheetController extends Controller
{
    public function get(Request $request)
    {
        $attendance = Timesheet::with('employee','shift')
        ->Filter($request)
        ->ofDate('date',$request->fromDate,$request->toDate)
        ->getOrPaginate($request);

        return success($attendance);
    }

    public function getAttendanceLog(Request $request)
    {
        $attendance = Timesheet::where('employeeId',$request->user()->employeeId)
        ->FilterYearMonth($request)
        ->get();

        return success(TimesheetParser::attendanceLog($attendance));
    }

    public function clockIn(){
        $algo = new TimesheetAlgo();
        return $algo->clockIn();
    }

    public function clockOut(){
        $algo = new TimesheetAlgo();
        return $algo->clockOut();
    }

    public function generateAttendanceExcel(Request $request)
    {
        SendEmailTimesheetExcelJob::dispatch($request->year,$request->user()->email);
        return success();
    }

    public function generateAttendancePdf(Request $request)
    {
        $pdf = new GenerateTimesheetPdf();
        return $pdf->generate($request);
    }
}
