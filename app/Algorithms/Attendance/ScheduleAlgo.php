<?php

namespace App\Algorithms\Attendance;

use App\Models\Attendance\Leave;
use App\Models\Attendance\Shift;
use Illuminate\Http\Request;
use App\Models\Attendance\Schedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\Constant\Attendance\ScheduleType;
use App\Models\Attendance\PublicHoliday;
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
                    ->saveActivity("Enter new  schedule: {$this->schedule->date},[{$this->schedule->id}]");

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

        $existingSchedule = Schedule::where($attribute)
        ->where('typeId', ScheduleType::LEAVE_ID)
        ->exists();
        if ($existingSchedule) {
            errScheduleLeave();
        }

        return Schedule::updateOrCreate($attribute,  [
            'typeId' => $request->type,
            'referenceType' =>  $scheduleType['model'],
            'reference' => $request->reference,
        ] + $createdBy);

    }

    public function scheduleType($type,$reference){
        $data = [];
        switch ($type) {
            case ScheduleType::PUBLIC_HOLIDAY_ID:
                $data['model'] = PublicHoliday::class;
                if(!$model = PublicHoliday::find($reference)){
                    errPublicHolidayGet();
                }
                break;

            case ScheduleType::WEEKLY_DAY_OFF_ID:
                $data['model'] = null;
                break;

            case ScheduleType::SHIFT_ID:
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
