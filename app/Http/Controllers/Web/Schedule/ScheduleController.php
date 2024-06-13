<?php

namespace App\Http\Controllers\Web\Schedule;

use Illuminate\Http\Request;
use App\Models\Schedule\Schedule;
use App\Http\Controllers\Controller;
use App\Algorithms\Schedule\ScheduleAlgo;

class ScheduleController extends Controller
{
    public function get(Request $request)
    {
        $schedule = Schedule::with('employee')->getOrPaginate($request);
        return success($schedule);
    }

    public function create(Request $request)
    {
        $algo = new ScheduleAlgo();
        return $algo->create($request);
    }
}
