<?php

namespace App\Models\Attendance;

use App\Models\BaseModel;
use App\Models\Employee\Employee;
use App\Parser\Leave\LeaveParser;
use App\Models\Attendance\Schedule;
use App\Services\Constant\ScheduleType;
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
                $query->where('date', 'LIKE', "%$request->search%");
            }

        });
    }

    public function delete()
    {
        $publicHolidaySchedule = Schedule::where('reference',$this->id)
        ->where('typeId',ScheduleType::LEAVE_ID)
        ->delete();

        return parent::delete();
    }



}
