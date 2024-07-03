<?php

namespace App\Models\Attendance;

use App\Models\BaseModel;
use App\Models\Employee\Employee;
use App\Models\Attendance\Schedule;
use App\Parser\Attendance\LeaveParser;
use App\Services\Constant\Attendance\ScheduleType;
use App\Models\Attendance\Traits\HasActivityLeaveProperty;

class Leave extends BaseModel
{
    use HasActivityLeaveProperty;

    protected $table = 'attendance_leaves';
    protected $guarded = ['id'];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime'
    ];

    public $parserClass = LeaveParser::class;

    /** --- RELATIONSHIPS --- */

    public function employee()
    {
        return $this->belongsTo(Employee::class,'employeeId');
    }

    public function schedules()
    {
        return $this->morphMany(Schedule::class,'scheduleable','referenceType', 'reference', 'id');
    }

    /** --- SCOPES --- */

    public function scopeFilter($query, $request)
    {
        return $query->where(function ($query) use ($request) {

            if ($this->hasSearch($request)) {

                $searchTerm = $request->search;
                $query->whereHas('employee', function ($query) use ($searchTerm) {
                    $query->where('name', 'LIKE', "%$searchTerm%")
                            ->orWhere('number', 'LIKE', "%$searchTerm%");
                });
            }

        });
    }

    public function delete()
    {
        $leaveSchedule = Schedule::where('reference',$this->id)
        ->where('typeId',ScheduleType::LEAVE_ID)
        ->exists();

        if($leaveSchedule){
            errScheduleAlreadyExist("Cannot delete leave");
        }

        return parent::delete();
    }

}
