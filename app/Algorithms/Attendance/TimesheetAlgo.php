<?php

namespace App\Algorithms\Attendance;

use Carbon\Carbon;
use App\Models\Attendance\Shift;
use App\Models\Attendance\Schedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance\Timesheet;
use App\Services\Constant\Attendance\ScheduleType;
use App\Services\Constant\Attendance\TimesheetStatus;
use App\Services\Constant\Activity\ActivityAction;

class TimesheetAlgo
{
    public function __construct(public ? Timesheet $attendance = null )
    {
    }

    public function clockIn()
    {
        try{

            DB::transaction(function () {

                $user = auth()->user();
                $currentTime = Carbon::now();
                $shift = $this->validateClockIn($user->employee->id,$currentTime);

                $result = $this->availableAttendance($shift,$currentTime,'clockin');

                if(!$result['available']){
                    errAttendanceCannotAbsent($shift->name.', Clock-In jam : '.$result['startTime'].' - '.$result['midPointTime']);
                }

                $dataInput = [
                    'employeeId' => $user->employee->id,
                    'date' =>  Carbon::today()->toDateString(),
                    'shiftId' => $shift->id,
                    'clockIn' => $currentTime,
                    'statusId' => TimesheetStatus::NO_CLOCK_OUT_ID
                ];

                $this->attendance = Timesheet::create($dataInput);

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

        $isAttendanceExist = Timesheet::where('employeeId', $employeeId)
        ->whereDate('date',Carbon::today()->toDateString())->exists();

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
        $oneDayAgo = $now->subDay();
        $attendance = Timesheet::where('employeeId', $employeeId)
        ->where('clockIn', '>=', $oneDayAgo)
        ->where('shiftId',$shift->id)
        ->latest('clockIn')
        ->first();

        if (!$attendance) {
            return $this->createNoClockInAttendance($employeeId,$currentTime,$shift);
        }
            return $this->updateAttendance($attendance, $currentTime);
    }

    private function createNoClockInAttendance($employeeId,$currentTime,$shift){

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
            ->saveActivity("Enter new " . $attendance->getTable() . ":  [$attendance->id]");

        return $attendance;
    }

    private function updateAttendance($attendance, $currentTime){
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

        $midPointTime = $startTime->copy()->addHours($startTime->diffInHours($endTime) / 2);

        if ($timesheet == 'clockin') {
            $available = $currentDateTime->between($startTime->copy()->subHours(2), $midPointTime);
        } else if($timesheet == 'clockout') {
            $available = $currentDateTime->gt($midPointTime);
        }

        return [
            'available' => $available,
            'startTime' => $startTime->copy()->subHours(2)->format('H:i:s'),
            'midPointTime' => $midPointTime->format('H:i:s')
        ];

    }


}
