<?php

namespace App\Http\Controllers\Web\Attendance;

use Illuminate\Http\Request;
use App\Models\Attendance\Leave;
use App\Http\Controllers\Controller;
use App\Algorithms\Attendance\LeaveAlgo;
use App\Http\Requests\Attendance\LeaveRequest;

class LeaveController extends Controller
{
    public function get(Request $request)
    {
        $leave = Leave::with('employee')->filter($request)->getOrPaginate($request);
        return success($leave);
    }

    public function create(LeaveRequest $request)
    {
        $algo = new LeaveAlgo();
        return $algo->create($request);
    }

    public function delete($id)
    {
        $leave = Leave::find($id);
        if(!$leave){
            errLeaveGet();
        }

        $algo = new LeaveAlgo($leave);
        return $algo->delete();
    }

    public function approveLeave($id)
    {
        $leave = Leave::find($id);
        if(!$leave){
            errLeaveGet();
        }

        $algo = new LeaveAlgo($leave);
        return $algo->approveLeave();
    }

}
