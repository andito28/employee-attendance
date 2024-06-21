<?php

namespace App\Http\Controllers\Web\Attendance;

use Illuminate\Http\Request;
use App\Models\Attendance\Schedule;
use App\Http\Controllers\Controller;
use App\Parser\Attendance\ScheduleParser;
use App\Algorithms\Attendance\ScheduleAlgo;

class ScheduleController extends Controller
{
    public function get(Request $request)
    {
        $schedule = Schedule::with('scheduleable')->getOrPaginate($request,true);
        return success(ScheduleParser::getMapping($schedule),pagination:pagination($schedule));
    }

    public function create(Request $request)
    {
        $algo = new ScheduleAlgo();
        return $algo->create($request);
    }

}
