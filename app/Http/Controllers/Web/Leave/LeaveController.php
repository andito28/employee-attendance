<?php

namespace App\Http\Controllers\Web\Leave;

use App\Models\Leave\Leave;
use Illuminate\Http\Request;
use App\Algorithms\Leave\LeaveAlgo;
use App\Http\Controllers\Controller;

class LeaveController extends Controller
{
    public function get(Request $request)
    {
        $leave = Leave::with('employee')->filter($request)->getOrPaginate($request);
        return success($leave);
    }

    public function create(Request $request)
    {
        $algo = new LeaveAlgo();
        return $algo->create($request);
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
