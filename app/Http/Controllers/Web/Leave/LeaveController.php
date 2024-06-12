<?php

namespace App\Http\Controllers\Web\Leave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function get(Request $request)
    {
        $leave = Leave::with('employee')->filter($request)->getOrPaginate($request);
        return success($leave);
    }
}
