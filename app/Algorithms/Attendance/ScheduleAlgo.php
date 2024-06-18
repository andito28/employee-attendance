<?php

namespace App\Algorithms\Attendance;

use App\Models\Leave\Leave;
use App\Models\Shift\Shift;
use Illuminate\Http\Request;
use App\Models\Schedule\Schedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\Constant\ScheduleType;
use App\Models\PublicHoliday\PublicHoliday;
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

                $this->schedule = $this->assignSchedule($request,$createdBy);

                $this->schedule->setActivityPropertyAttributes(ActivityAction::CREATE)
                    ->saveActivity("Enter new " .$this->schedule->getTable() .
                    ":[$this->schedule->id]");

            });

            return success($this->schedule);

        } catch (\Exception $exception) {
            exception($exception);
        }
    }

    /** --- SUB FUNCTIONS --- */

    private function assignSchedule($request,$createdBy)
    {
        $scheduleType = $this->scheduleType($request->type,$request->reference);
        if(!$scheduleType){
            errScheduleInvalidType();
        }

        $attribute = [
            'employeeId' => $request->employeeId,
            'date' => $request->date,
        ];

        $dataInput = [
            'typeId' => $request->type,
            'scheduleableType' =>  $scheduleType['model'],
            'scheduleableId' => $request->reference,
        ];

        $existingSchedule = Schedule::where($attribute)
        ->where('typeId', ScheduleType::LEAVE_ID)
        ->exists();
        if ($existingSchedule) {
            errScheduleLeave();
        }

        $schedule = Schedule::updateOrCreate($attribute, $dataInput + $createdBy);
        return $schedule;

    }

    public function scheduleType($type,$reference){
        $data = [];
        switch ($type) {
            case 1:
                $data['model'] = PublicHoliday::class;
                if(!$model = PublicHoliday::find($reference)){
                    errPublicHolidayGet();
                }
                break;

            case 2:
                $data['model'] = null;
                break;

            case 4:
                $data['model'] = Shift::class;
                if(!$model = Shift::find($reference)){
                    errShiftGet();
                }
                break;

            default:
                return null;
        }

        return $data;
    }
}
