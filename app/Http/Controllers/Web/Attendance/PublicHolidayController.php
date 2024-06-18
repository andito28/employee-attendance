<?php

namespace App\Http\Controllers\Web\Attendance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Attendance\PublicHoliday;
use App\Algorithms\Attendance\PublicHolidayAlgo;
use App\Http\Requests\Attendance\PublicHolidayRequest;

class PublicHolidayController extends Controller
{
    public function get(Request $request)
    {
        $publicHoliday = PublicHoliday::filter($request)->getOrPaginate($request,true);
        return success($publicHoliday);
    }

    public function create(PublicHolidayRequest $request)
    {
        $algo = new PublicHolidayAlgo();
        return $algo->create($request);
    }

    public function update($id,PublicHolidayRequest $request)
    {
        $publicHoliday = PublicHoliday::find($id);
        if(!$publicHoliday){
            errPublicHolidayGet();
        }

        $algo = new PublicHolidayAlgo($publicHoliday);
        return $algo->update($request);
    }

    public function delete($id)
    {
        $publicHoliday = PublicHoliday::find($id);
        if(!$publicHoliday){
            errPublicHolidayGet();
        }

        $algo = new PublicHolidayAlgo($publicHoliday);
        return $algo->delete();
    }

    public function assignSchedule($id)
    {
        $publicHoliday = PublicHoliday::find($id);
        if(!$publicHoliday){
            errPublicHolidayGet();
        }

        $algo = new PublicHolidayAlgo($publicHoliday);
        return $algo->assignSchedule();
    }
}
