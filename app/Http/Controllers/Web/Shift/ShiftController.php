<?php

namespace App\Http\Controllers\Web\Shift;

use Illuminate\Http\Request;
use App\Algorithms\Shift\ShiftAlgo;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shift\ShiftRequest;

class ShiftController extends Controller
{
    public function get()
    {
        $shift = Shift::filter($request)->getOrPaginate($request, true);
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
