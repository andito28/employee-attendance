<?php

namespace App\Algorithms\Attendance;

use Carbon\Carbon;
use App\Models\Leave\Leave;
use Illuminate\Http\Request;
use App\Models\Employee\Employee;
use App\Models\Attendance\Schedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Services\Constant\RoleUser;
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
                    if($user->roleId != RoleUser::ADMINISTRATOR_ID){
                        errAccessPemission();
                    }
                    $this->leave = $this->createLeave($request,$createdBy,
                    LeaveStatus::APPROVE_ID,$request->employeeId);
                }else{
                    $this->leave = $this->createLeave($request,$createdBy,
                    LeaveStatus::PENDING_ID,$user->employee->id);
                }

                $this->leave->setActivityPropertyAttributes(ActivityAction::CREATE)
                    ->saveActivity("Enter new " .$this->leave->getTable() . ":[$this->leave->id]");

            });

            return success($this->leave);

        } catch (\Exception $exception) {
            exception($exception);
        }
    }

    public function delete()
    {
        try {

            DB::transaction(function (){

                $this->leave->setOldActivityPropertyAttributes(ActivityAction::DELETE);

                $now = Carbon::now();
                $date = Carbon::parse($this->leave->fromDate);
                $diffInMonths = abs($now->diffInMonths($date));

                if($diffInMonths > 1){
                    errLeaveDelete();
                }

                $user = auth()->user();
                if($user->roleId == RoleUser::EMPLOYEE_ID){
                    $leaveEmployee = Leave::where('id',$this->leave->id)
                    ->where('employeeId',$user->employee->id)->exists();
                    if(!$leaveEmployee){
                        errLeaveDelete();
                    }
                }

                $this->leave->delete();

                $this->leave->setActivityPropertyAttributes(ActivityAction::DELETE)
                ->saveActivity("Delete Leave : [{$this->leave->id}]");

            });

            return success();

        } catch (\Exception $exception) {
            exception($exception);
        }
    }

    public function approveLeave()
    {
        try {

            DB::transaction(function () {

                $user = auth()->user();
                $createdBy = [
                    'createdBy' =>  $user->employee->id,
                    'createdByName' =>  $user->employee->name
                ];

                $this->leave->setOldActivityPropertyAttributes(ActivityAction::UPDATE);

                $this->validateApprovedLeave($user->employee->id);

                $this->leave->update(['statusId' => LeaveStatus::APPROVE_ID]);

                $this->assignSchedule($this->leave,$createdBy);

                $this->leave->setActivityPropertyAttributes(ActivityAction::UPDATE)
                ->saveActivity("Approve Leave :  [{$this->leave->id}]");

            });

            return success($this->leave);

        } catch (\Exception $exception) {
            exception($exception);
        }
    }


    /** --- SUB FUNCTIONS --- */

    private function createLeave($request,$createdBy,$statusId,$employeeId)
    {
        $employee = Employee::find($employeeId);
        if(!$employee){
            errEmployeeGet();
        }

        $this->validateLeaveDates($request,$employeeId);

        $dataLeave = [
            'employeeId' => $employeeId,
            'fromDate' => $request->fromDate,
            'toDate' => $request->toDate,
            'notes' => $request->notes,
            'statusId' => $statusId
        ];

        $leave = Leave::create($dataLeave + $createdBy);
        if($statusId == LeaveStatus::APPROVE_ID){
            $this->assignSchedule($leave,$createdBy);
        }

        return $leave;
    }

    private function validateLeaveDates($request,$employeeId)
    {
        $today = Carbon::today();
        $fromDate = Carbon::parse($request->fromDate);
        $toDate = Carbon::parse($request->toDate);

        if($fromDate < $today || $fromDate > $toDate){
            errLeaveValidateDate();
        }

        $isleave = Leave::where('employeeId',$employeeId)
        ->whereDate('fromDate', '<=', $request->fromDate)
        ->whereDate('toDate', '>=', $request->fromDate)
        ->first();

        if($isleave){
            errLeaveValidateDate("telah mengajukan cuti $isleave->fromDate - $isleave->toDate");
        }

        $daysDifference = $fromDate->diffInDays($toDate) + 1;
        if ($daysDifference > 7) {
            errLeaveDurationMax("maksimal 7 hari");
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
        $fromDate = Carbon::parse($leave->fromDate);
        $toDate = Carbon::parse($leave->toDate);

        $datesInRange = collect(Carbon::parse($fromDate)->toPeriod($toDate))
        ->map(function ($date) {
            return $date->format('Y-m-d');
        });

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

    private function validateApprovedLeave($employeeId)
    {
        if($employeeId == $this->leave->employeeId){
            errLeaveApproveUnauthorized();
        }

        if($this->leave->statusId == LeaveStatus::APPROVE_ID){
            errLeaveAlreadyApprove();
        }

        $fromDate = Carbon::parse($this->leave->fromDate);
        $toDate = Carbon::parse($this->leave->toDate);
        $daysDifference = $fromDate->diffInDays($toDate) + 1;

        $this->validateYearlyLeaveLimit($this->leave->id,$daysDifference);
    }

}
