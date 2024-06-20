<?php

namespace App\Http\Controllers\Web\Attendance;

use Illuminate\Http\Request;
use App\Mail\TimesheetExportMail;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\SendEmailTimesheetExcelJob;
use App\Algorithms\Attendance\TimesheetAlgo;
use App\Services\Excel\GenerateTimesheetExcel;


class TimesheetController extends Controller
{
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
        $email = $request->user()->email;
        $year = $request->year;
        SendEmailTimesheetExcelJob::dispatch($year,$email);
    }

    public function generateAttendancePdf()
    {

    }
}
