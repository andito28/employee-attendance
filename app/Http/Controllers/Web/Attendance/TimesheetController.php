<?php

namespace App\Http\Controllers\Web\Attendance;

use Illuminate\Http\Request;
use App\Mail\TimesheetExportMail;
use App\Http\Controllers\Controller;
use App\Models\Attendance\Timesheet;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\Attendance\SendEmailTimesheetExcelJob;
use App\Parser\Attendance\TimesheetParser;
use App\Services\PDF\GenerateTimesheetPdf;
use App\Algorithms\Attendance\TimesheetAlgo;
use App\Models\Attendance\TimesheetCorrection;
use App\Services\Excel\GenerateTimesheetExcel;
use App\Http\Requests\Attendance\TimesheetCorrectionRequest;


class TimesheetController extends Controller
{
    public function get(Request $request)
    {
        $attendance = Timesheet::with('employee','shift')
        ->Filter($request)
        ->ofDate('date',$request->fromDate,$request->toDate)
        ->getOrPaginate($request,true);

        return success($attendance);
    }

    public function getAttendanceLog(Request $request)
    {
        $attendance = Timesheet::where('employeeId',$request->user()->employeeId)
        ->FilterYearMonth($request)
        ->get();

        return success(TimesheetParser::attendanceLog($attendance,$request));
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

    public function getCorrection(Request $request)
    {
        $correction = TimesheetCorrection::with('employee')
        ->ofDate('date',$request->fromDate,$request->toDate)
        ->getOrPaginate($request,true);
        return success($correction);
    }

    public function correction(TimesheetCorrectionRequest $request)
    {
        $algo = new TimesheetAlgo();
        return $algo->correction(TimesheetCorrection::class,$request);
    }

    public function approvalCorrection(Request $request,$id)
    {
        $correction = TimesheetCorrection::find($id);
        if(!$correction){
            errCorrectionGet();
        }

        $algo = new TimesheetAlgo();
        return $algo->approvalCorrection($correction,$request);
    }
}
