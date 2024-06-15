<?php

namespace App\Http\Controllers\Web\Attendance;

use App\Http\Controllers\Controller;
use App\Algorithms\Attendance\AttendanceAlgo;

class AttendanceController extends Controller
{
    public function clockIn(){
        $algo = new AttendanceAlgo();
        return $algo->clockIn();
    }

    public function clockOut(){
        $algo = new AttendanceAlgo();
        return $algo->clockOut();
    }
}
