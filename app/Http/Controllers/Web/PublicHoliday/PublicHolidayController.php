<?php

namespace App\Http\Controllers\Web\PublicHoliday;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PublicHoliday\PublicHoliday;

class PublicHolidayController extends Controller
{
    public function get(Request $request)
    {
        $publicHoliday = PublicHoliday::filter($request)->getOrPaginate($request,true);
        return success($publicHoliday);
    }
}
