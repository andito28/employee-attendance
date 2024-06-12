<?php

namespace App\Algorithms\Leave;

use Carbon\Carbon;
use App\Models\Leave\Leave;
use Illuminate\Http\Request;
use App\Models\Employee\Employee;
use App\Models\Schedule\Schedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Services\Constant\LeaveStatus;
use App\Services\Constant\ScheduleType;
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

        $this->validateLeaveDates($request,$request->employeeId);

        $dataLeave = [
            'employeeId' => $request->employeeId,
            'fromDate' => $request->fromDate,
            'toDate' => $request->toDate,
            'notes' => $request->notes,
            'statusId' => LeaveStatus::APPROVE_ID
        ];

        $leave = Leave::create($dataLeave + $createdBy);
        $this->assignSchedule($leave,$createdBy);

        return $leave;
    }

    private function createLeaveByEmployee($request,$createdBy)
    {
        //
    }

    private function validateLeaveDates($request,$employeeId)
    {
        $today = Carbon::today();
        $fromDate = Carbon::parse($request->fromDate);
        $toDate = Carbon::parse($request->toDate);

        if($fromDate < $today || $fromDate > $toDate){
            errLeaveValidateDate();
        }

        $daysDifference = $fromDate->diffInDays($toDate) + 1;
        if ($daysDifference > 7) {
            errLeaveDurationMax("maksimal 7 hari");
        }

        $isleave = Leave::where('employeeId',$employeeId)
                    ->whereDate('fromDate', '<=', $request->fromDate)
                    ->whereDate('toDate', '>=', $request->fromDate)
                    ->first();

        if($isleave){
            errLeaveValidateDate("telah mengajukan cuti $request->fromDate - $request->toDate");
        }

        $this->validateYearlyLeaveLimit($employeeId,$daysDifference);
    }

    private function validateYearlyLeaveLimit($employeeId,$daysDifference)
    {
        $currentYear = Carbon::now()->year;
        $leaves = Leave::where('employeeId', $employeeId)
        ->where('statusId', LeaveStatus::APPROVE_ID)
        ->where(function ($query) use ($currentYear) {
            $query->whereYear('fromDate', $currentYear)
            ->orWhereYear('toDate', $currentYear);
        })->get();

        $totalDayLeaves = $leaves->sum(function ($leave) {
                        $fromDate = Carbon::parse($leave->fromDate);
                        $toDate = Carbon::parse($leave->toDate);
                        return $fromDate->diffInDays($toDate) + 1;
                    });

        if (($totalDayLeaves + $daysDifference) > 12) {
        errLeaveDurationMax("cuti maksimal 12 kali dalam setahun");
        }
    }

    private function assignSchedule($leave,$createdBy)
    {
        $today = Carbon::today();
        $fromDate = Carbon::parse($leave->fromDate);
        $toDate = Carbon::parse($leave->toDate);

        $datesInRange = [];
        for ($date = $fromDate; $date->lte($toDate); $date->addDay()) {
            $datesInRange[] = $date->format('Y-m-d');
        }

        foreach($datesInRange as $date){
            $dataSchedule = [
                'employeeId' => $leave->employeeId,
                'scheduleableId' => $leave->id,
                'scheduleableType' => Leave::class,
                'typeId' => ScheduleType::LEAVE_ID,
                'date' => $date
            ];

            Schedule::updateOrCreate(
                ['employeeId' => $leave->employeeId, 'date' => $date],
                $dataSchedule + $createdBy
            );
        }

    }
}
