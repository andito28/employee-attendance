<?php

namespace App\Algorithms\Attendance;

use Carbon\Carbon;
use App\Models\Attendance\Shift;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Attendance\Schedule;
use App\Models\Attendance\Timesheet;
use Illuminate\Support\Facades\Auth;
use App\Services\Constant\Activity\ActivityAction;
use App\Services\Constant\Attendance\ScheduleType;
use App\Services\Constant\Attendance\TimesheetStatus;
use App\Services\Constant\Attendance\TimesheetCorrectionApproval;

class TimesheetAlgo
{

    public function __construct(public ? Timesheet $timesheet = null )
    {
    }

    public function clockIn()
    {
        try{

            DB::transaction(function () {

                $user = auth()->user();
                $currentTime = Carbon::now();
                $shift =  $this->getScheduleShift($user->employee->id,$currentTime);

                $result = $this->availableAttendance($shift,$currentTime,'clockin');

                if(!$result['available']){
                    errAttendanceCannotAbsent($shift->name.', Clock-In jam : '.$result['startTime'].' - '.$result['midPointTime']);
                }

                $this->validateClockIn($user->employee->id,$currentTime);

                $this->timesheet = Timesheet::create([
                    'employeeId' => $user->employee->id,
                    'date' =>  Carbon::today()->toDateString(),
                    'shiftId' => $shift->id,
                    'clockIn' => $currentTime,
                    'statusId' => TimesheetStatus::NO_CLOCK_OUT_ID
                ]);

                $this->timesheet->setActivityPropertyAttributes(ActivityAction::CREATE)
                    ->saveActivity("Enter new timesheet : {$this->timesheet->date}, [{$this->timesheet->id}]");

            });

            return success($this->timesheet);

        }catch (\Exception $exception) {
            exception($exception);
        }
    }

    public function clockOut()
    {
        try {

            DB::transaction(function (){

                $user = auth()->user();
                $currentTime = Carbon::now();
                $shift = $this->getScheduleShift($user->employee->id, $currentTime);

                $result = $this->availableAttendance($shift,$currentTime,'clockout');

                if(!$result['available']){
                    errAttendanceCannotAbsent($shift->name.', Clock-Out diatas jam : '.$result['midPointTime']);
                }

                $isAttendance = Timesheet::where('employeeId',$user->employee->id)
                    ->whereDate('clockOut',$currentTime)
                    ->where('shiftId',$shift->id)
                    ->exists();

                if($isAttendance){
                    errAttendanceAlreadyExist();
                }

                $this->timesheet = $this->createClockOutAttendance($user->employee->id,$currentTime, $shift);

            });

            return success($this->timesheet->fresh());

        } catch (\Exception $exception) {
            return exception($exception);
        }
    }

    public function correction($timesheetCorrection,$request)
    {
        try {

            $correction = DB::transaction(function () use ($timesheetCorrection,$request) {

                $user = auth()->user();
                $conditions = [
                    'employeeId' => $user->employee->id,
                    'date' => $request->date,
                ];
                $status = [
                    'approvalId' => TimesheetCorrectionApproval::PENDING_ID,
                    'statusId' => TimesheetStatus::NOT_STATUS_ID
                ];

                $correction = $timesheetCorrection::updateOrCreate($conditions,$request->all() + $status);

                $correction->setActivityPropertyAttributes(ActivityAction::CREATE)
                    ->saveActivity("Enter new correction : {$correction->date},[{$correction->id}]");

                return $correction;

            });

            return success($correction);

        } catch (\Exception $exception) {
            exception($exception);
        }
    }

    public function approvalCorrection($correction,$request)
    {
        try {

            $correction = DB::transaction(function () use ($correction,$request) {

                $user = auth()->user();

                $correction->setOldActivityPropertyAttributes(ActivityAction::UPDATE);

                $status = $this->validateApprovalCorrection($request,$correction->employeeId,$correction->date);

                $correction->update([
                    'approvedBy' => $user->employee->id,
                    'approvedByName' => $user->employee->name,
                    'statusId' => $status,
                    'approvalId' => $request->approved,
                    'notes' => $request->notes
                ]);

                $correction->setActivityPropertyAttributes(ActivityAction::UPDATE)
                    ->saveActivity("Update Approval correction :{$correction->date}, [{$correction->id}]");

                return $correction;

            });

            return success($correction->fresh());

        } catch (\Exception $exception) {
            exception($exception);
        }
    }

    /** --- SUB FUNCTIONS --- */

    private function validateClockIn($employeeId,$currentTime)
    {
        $schedule = Schedule::where('employeeId',$employeeId)
        ->whereNot('typeId', '=', ScheduleType::SHIFT_ID)
        ->whereDate('date',$currentTime)->first();
        if($schedule){
            errScheduleAlreadyExist(ScheduleType::display($schedule->typeId));
        }

        $isAttendanceExist = Timesheet::where('employeeId', $employeeId)
        ->whereDate('date',Carbon::today()->toDateString())->exists();
        if ($isAttendanceExist) {
            errAttendanceAlreadyExist();
        }

    }

    private function getScheduleShift($employeeId, $currentTime)
    {
        $scheduleShift = Schedule::where('typeId',ScheduleType::SHIFT_ID)
        ->where('employeeId',$employeeId)
        ->whereDate('date',$currentTime)
        ->first();

        $shift = $scheduleShift ? Shift::find($scheduleShift->reference) : Shift::first();

        return $shift;
    }

    private function createClockOutAttendance($employeeId, $currentTime, $shift)
    {
        $now = Carbon::now();
        $oneDayAgo = $now->subDay();
        $attendance = Timesheet::where('employeeId', $employeeId)
        ->where('clockIn', '>=', $oneDayAgo)
        ->where('shiftId',$shift->id)
        ->latest('clockIn')
        ->first();

        if (!$attendance) {
            return $this->createNoClockInAttendance($employeeId,$currentTime,$shift);
        }
            return $this->saveClockOutAttendance($attendance, $currentTime);
    }

    private function createNoClockInAttendance($employeeId,$currentTime,$shift)
    {
        $startTime = Carbon::parse($shift->startTime);
        $endTime = Carbon::parse($shift->endTime);

        $date = $endTime < $startTime ? Carbon::yesterday()->toDateString()
                    : Carbon::today()->toDateString();

        $attendance = Timesheet::create([
            'employeeId' => $employeeId,
            'date' => $date,
            'shiftId' => $shift->id,
            'clockOut' => $currentTime,
            'statusId' => TimesheetStatus::NO_CLOCK_IN_ID,
        ]);

        $attendance->setActivityPropertyAttributes(ActivityAction::CREATE)
            ->saveActivity("Enter new timesheet: {$this->timesheet->date}, [{$attendance->id}]");

        return $attendance;
    }

    private function saveClockOutAttendance($attendance, $currentTime)
    {
        $startTimeShift = $attendance->shift->startTime;
        $timeClockIn = Carbon::parse($attendance->clockIn)->toTimeString();

        if($attendance->clockIn == null){
            $attendanceStatus = TimesheetStatus::NO_CLOCK_IN_ID;
        }else{
            $attendanceStatus = $timeClockIn <= $startTimeShift ? TimesheetStatus::VALID_ID : TimesheetStatus::LATE_ID;
        }

        $attendance->setOldActivityPropertyAttributes(ActivityAction::UPDATE);

        $attendance->update([
            'clockOut' => $currentTime,
            'statusId' => $attendanceStatus
        ]);

        $attendance->setActivityPropertyAttributes(ActivityAction::UPDATE)
            ->saveActivity("Update timesheet : {$this->timesheet->date}, [{$attendance->id}]");

        return $attendance;
    }

    private function availableAttendance($shift, $currentTime, $timesheet)
    {
        $currentDateTime = Carbon::parse($currentTime);
        $startTime = Carbon::parse($shift->startTime);
        $endTime = Carbon::parse($shift->endTime);

        if ($endTime < $startTime) {
            $endTime->addDay();
        }

        $midPointTime = $startTime->copy()->addHours($startTime->diffInHours($endTime) / 2);

        if ($timesheet == 'clockin') {
            $available = $currentDateTime->between($startTime->copy()->subHours(2), $midPointTime);
        } else {
            $available = $currentDateTime->gt($midPointTime);
        }

        return [
            'available' => $available,
            'startTime' => $startTime->copy()->subHours(2)->format('H:i:s'),
            'midPointTime' => $midPointTime->format('H:i:s')
        ];

    }

    private function validateApprovalCorrection($request,$employeeId,$date)
    {
        if($request->approved != TimesheetCorrectionApproval::APPROVED_ID &&
            $request->approved != TimesheetCorrectionApproval::DISAPPROVED_ID ){
                errCorrectionApproved();
            }

        if($request->approved == TimesheetCorrectionApproval::DISAPPROVED_ID
            && $request->notes == null){
                errCorrectionDisapprove();
        }

        $timesheet = Timesheet::where('employeeId',$employeeId)->where('date',$date)->first();
        $timesheetStatus = $timesheet->statusId ?? TimesheetStatus::NOT_STATUS_ID;

        return $request->approved == TimesheetCorrectionApproval::APPROVED_ID ?
        TimesheetStatus::VALID_ID : $timesheetStatus;

    }


}
