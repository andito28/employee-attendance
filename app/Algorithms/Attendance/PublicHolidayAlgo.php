<?php

namespace App\Algorithms\Attendance;

use Illuminate\Http\Request;
use App\Jobs\AssignScheduleJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Attendance\Schedule;
use App\Models\Attendance\PublicHoliday;
use App\Services\Constant\Activity\ActivityAction;

class PublicHolidayAlgo
{
    public function __construct(public ? PublicHoliday $publicHoliday = null )
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

                $this->publicHoliday = PublicHoliday::create($request->all() + $createdBy);

                $this->publicHoliday->setActivityPropertyAttributes(ActivityAction::CREATE)
                    ->saveActivity("Enter new " .$this->publicHoliday->getTable() . ":$this->publicHoliday->name [$this->publicHoliday->id]");

            });

            return success($this->publicHoliday);

        } catch (\Exception $exception) {
            exception($exception);
        }
    }

    public function update(Request $request)
    {
        try {

            DB::transaction(function () use ($request) {

                $this->publicHoliday->setOldActivityPropertyAttributes(ActivityAction::UPDATE);

                $this->publicHoliday->update($request->all());

                $this->publicHoliday->setActivityPropertyAttributes(ActivityAction::UPDATE)
                    ->saveActivity("Update " . $this->publicHoliday->getTable() . ": $this->publicHoliday->name [$this->publicHoliday->id]");

            });

            return success($this->publicHoliday->fresh());

        } catch (\Exception $exception) {
            exception($exception);
        }
    }

    public function delete()
    {
        try {

            DB::transaction(function (){

                $this->publicHoliday->setOldActivityPropertyAttributes(ActivityAction::DELETE);

                $this->publicHoliday->delete();

                $this->publicHoliday->setActivityPropertyAttributes(ActivityAction::DELETE)
                    ->saveActivity("Delete " . $this->publicHoliday->getTable() . ": $this->publicHoliday->name [$this->publicHoliday->id]");

            });

            return success();

        } catch (\Exception $exception) {
            exception($exception);
        }
    }

    public function assignSchedule()
    {
        try {

            DB::transaction(function () {

                $user = auth()->user();
                $createdBy = [
                    'createdBy' =>  $user->employee->id,
                    'createdByName' =>  $user->employee->name
                ];

                $this->createSchedule($createdBy);

                $this->publicHoliday->setActivityPropertyAttributes(ActivityAction::CREATE)
                    ->saveActivity("Assign Schedule " .$this->publicHoliday->getTable() . ":$this->publicHoliday->name [$this->publicHoliday->id]");

            });

            return success($this->publicHoliday);

        } catch (\Exception $exception) {
            exception($exception);
        }
    }

    /** --- SUB FUNCTIONS --- */

    private function createSchedule($createdBy)
    {
        if ($this->publicHoliday->assigned == true) {
            errPublicHolidayIsAssign();
        }

        AssignScheduleJob::dispatch($this->publicHoliday,$createdBy);
        $this->publicHoliday->update(['assigned' => true]);
    }

}
