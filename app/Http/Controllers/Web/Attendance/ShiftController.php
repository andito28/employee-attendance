<?php

namespace App\Http\Controllers\Web\Attendance;

use Illuminate\Http\Request;
use App\Models\Attendance\Shift;
use App\Http\Controllers\Controller;
use App\Algorithms\Attendance\ShiftAlgo;
use App\Http\Requests\Attendance\ShiftRequest;

class ShiftController extends Controller
{
    public function get(Request $request)
    {
        $shift = Shift::get();
        return success($shift);
    }

    public function create(ShiftRequest $request)
    {
        $algo = new ShiftAlgo();
        return $algo->create($request);
    }

    public function update($id,ShiftRequest $request)
    {
        $shift = Shift::find($id);
        if(!$shift){
            errShiftGet();
        }

        $algo = new ShiftAlgo($shift);
        return $algo->update($request);
    }

    public function delete($id)
    {
        $shift = Shift::find($id);
        if(!$shift){
            errShiftGet();
        }

        $algo = new ShiftAlgo($shift);
        return $algo->delete();
    }

}
