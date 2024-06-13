<?php

namespace App\Algorithms\Schedule;

use Illuminate\Http\Request;
use App\Models\Schedule\Schedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\Constant\Activity\ActivityAction;

class ScheduleAlgo
{
    public function __construct(public ? Schedule $schedule = null)
    {
    }

    public function create(Request $request)
    {
        try {

            DB::transaction(function () use ($request) {

                $user = auth()->user();
                $createdBy = [
                    'createdBy' =>  $user->employee->id,
                    'createdByName' =>  $user->employee->name
                ];

                $this->schedule = $this->createSchedule($request,$createdBy);

                $this->schedule->setActivityPropertyAttributes(ActivityAction::CREATE)
                    ->saveActivity("Enter new " .$this->schedule->getTable() .
                    ":[$this->schedule->id]");

            });

            return success($this->schedule);

        } catch (\Exception $exception) {
            exception($exception);
        }
    }
}
