<?php

namespace App\Algorithms\Leave;

use Carbon\Carbon;
use App\Models\Leave\Leave;
use Illuminate\Http\Request;
use App\Models\Employee\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Services\Constant\Activity\ActivityAction;

class LeaveAlgo
{
    public function __construct(public ? Leave $leave = null )
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

                if($request->has('employeeId')){
                    $this->leave = $this->createLeaveByAdmin($request,$createdBy);
                }else{
                    $this->leave = $this->createLeaveByEmployee($request,$createdBy);
                }

                $this->leave->setActivityPropertyAttributes(ActivityAction::CREATE)
                    ->saveActivity("Enter new " .$this->leave->getTable() . ":[$this->leave->id]");

            });

            return success($this->leave);

        } catch (\Exception $exception) {
            exception($exception);
        }
    }


    /** --- SUB FUNCTIONS --- */

    private function createLeaveByAdmin($request,$createdBy)
    {
        $employee = Employee::find($request->employeeId);
        if(!$employee){
            errEmployeeGet();
        }

        $this->validateLeaveDates($request);




        $this->assignSchedule($request,$createdBy);
    }

    private function createLeaveByEmployee($request,$createdBy)
    {


    }

    private function validateLeaveDates($request)
    {
        $today = Carbon::today();
        $fromDate = Carbon::parse($request->fromDate);
        $toDate = Carbon::parse($request->toDate);

        if($fromDate < $today || $fromDate > $toDate){
            errLeaveValidateDate();
        }

        $daysDifference = $fromDate->diffInDays($toDate);
        if ($daysDifference > 7) {
            errLeaveDurationMax("maximal 7 hari");
        }

    }

    private function assignSchedule($request,$createdBy)
    {

    }
}
