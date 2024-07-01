<?php

namespace App\Http\Controllers\Web\Attendance;

use Illuminate\Http\Request;
use App\Models\Attendance\Schedule;
use App\Http\Controllers\Controller;
use App\Algorithms\Attendance\ScheduleAlgo;

class ScheduleController extends Controller
{
    public function get(Request $request)
    {
        $schedule = Schedule::with('employee','scheduleable')
        ->ofDate('date',$request->fromDate,$request->toDate)
        ->getOrPaginate($request,true);
        return success($schedule);
    }

    public function create(Request $request)
    {
        $algo = new ScheduleAlgo();
        return $algo->create($request);
    }

}
