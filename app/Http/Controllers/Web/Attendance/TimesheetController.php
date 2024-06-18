<?php

namespace App\Http\Controllers\Web\Attendance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Algorithms\Attendance\TimesheetAlgo;


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

    public function generateAttendanceExcel()
    {

    }

    public function generateAttendancePdf()
    {

    }
}
