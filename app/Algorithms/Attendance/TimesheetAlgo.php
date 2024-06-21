<?php

namespace App\Algorithms\Attendance;

use Carbon\Carbon;
use App\Models\Attendance\Shift;
use App\Models\Attendance\Schedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance\Attendance;
use App\Services\Constant\ScheduleType;
use App\Services\Constant\AttendanceStatus;
use App\Services\Constant\Activity\ActivityAction;

class TimesheetAlgo
{
    public function __construct(public ? Attendance $attendance = null )
    {
    }

    public function clockIn()
    {
        try{

            DB::transaction(function () {

                $user = auth()->user();
                $currentTime = Carbon::now();
                $shift = $this->validateClockIn($user->employee->id,$currentTime);

                if(!$this->availableAttendance($shift,$currentTime,'clockin')){
                    errAttendanceCannotAbsent();
                }

                $dataInput = [
                    'employeeId' => $user->employee->id,
                    'shiftId' => $shift->id,
                    'clockIn' => $currentTime,
                    'statusId' => AttendanceStatus::NO_CLOCK_OUT_ID
                ];

                $this->attendance = Attendance::create($dataInput);

                $this->attendance->setActivityPropertyAttributes(ActivityAction::CREATE)
                        ->saveActivity("Enter new " . $this->attendance->getTable() . ":  [$this->attendance->id]");

            });

            return success($this->attendance);

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

                if(!$this->availableAttendance($shift,$currentTime,'clockout')){
                    errAttendanceCannotAbsent();
                }

                $isAttendance = Attendance::where('employeeId',$user->employee->id)
                    ->whereDate('clockOut',$currentTime)
                    ->where('shiftId',$shift->id)
                    ->exists();

                    if($isAttendance){
                        errAttendanceAlreadyExist();
                    }

                    $this->attendance = $this->saveAttendanceClockOut($user->employee->id,
                    $currentTime, $shift);

            });

            return success($this->attendance->fresh());
        } catch (\Exception $exception) {
            return exception($exception);
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

        $isAttendanceExist = Attendance::where('employeeId', $employeeId)
        ->where(function ($query) use ($currentTime) {
        $query->whereDate('clockIn', $currentTime)
        ->orWhere(function ($query) use ($currentTime) {
        $query->whereNull('clockIn')
        ->whereDate('clockOut', $currentTime);
        });
        })->exists();

        if ($isAttendanceExist) {
            errAttendanceAlreadyExist();
        }

        return $this->getScheduleShift($employeeId,$currentTime);

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

    private function saveAttendanceClockOut($employeeId, $currentTime, $shift){

        $now = Carbon::now();
        $yesterday = $now->subDay();
        $attendance = Attendance::where('employeeId', $employeeId)
        ->where('clockIn', '>=', $yesterday)
        ->latest('clockIn')
        ->first();

        if (!$attendance) {
            return $this->createNoClockInAttendance($employeeId,$currentTime,$shift);
        }
            return $this->updateAttendance($attendance, $currentTime);
    }

    private function createNoClockInAttendance($employeeId,$currentTime,$shift){

        $attendance = Attendance::create([
            'employeeId' => $employeeId,
            'shiftId' => $shift->id,
            'clockOut' => $currentTime,
            'statusId' => AttendanceStatus::NO_CLOCK_IN_ID,
        ]);

        $attendance->setActivityPropertyAttributes(ActivityAction::CREATE)
            ->saveActivity("Enter new " . $attendance->getTable() . ":  [$attendance->id]");

        return $attendance;
    }

    private function updateAttendance($attendance, $currentTime){
        $startTimeShift = $attendance->shift->startTime;
        $timeClockIn = Carbon::parse($attendance->clockIn)->toTimeString();

        if($attendance->clockIn == null){
            $attendanceStatus = AttendanceStatus::NO_CLOCK_IN_ID;
        }else{
            $attendanceStatus = $timeClockIn <= $startTimeShift ? AttendanceStatus::VALID_ID : AttendanceStatus::LATE_ID;
        }

        $attendance->setOldActivityPropertyAttributes(ActivityAction::UPDATE);

        $attendance->update([
            'clockOut' => $currentTime,
            'statusId' => $attendanceStatus
        ]);

        $attendance->setActivityPropertyAttributes(ActivityAction::UPDATE)
            ->saveActivity("Update " . $attendance->getTable() . ": [$attendance->id]");

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

        $midPoint = $startTime->copy()->addHours($startTime->diffInHours($endTime) / 2);

        if ($timesheet == 'clockin') {
            $available = $currentDateTime->between($startTime->copy()->subHours(2), $midPoint);
        } else {
            $available = ($currentDateTime > $midPoint);
        }

        return $available;
    }


}
