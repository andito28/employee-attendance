<?php

namespace App\Models\Attendance;

use App\Models\BaseModel;
use App\Models\Employee\Employee;
use App\Models\Attendance\Schedule;
use App\Models\Attendance\Attendance;
use App\Parser\Attendance\ShiftParser;
use App\Services\Constant\Attendance\ScheduleType;
use App\Models\Attendance\Traits\HasActivityShiftProperty;

class Shift extends BaseModel
{
    use HasActivityShiftProperty;

    protected $table = 'attendance_shifts';
    protected $guarded = ['id'];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime'
    ];

    public $parserClass = ShiftParser::class;

    /** --- RELATIONSHIPS --- */
    public function employees(){
        return $this->belongsToMany(Employee::class);
    }

    public function attendance(){
        return $this->belongsTo(Attendance::class,'shiftId');
    }

    public function schedules()
    {
        return $this->morphMany(Schedule::class, 'scheduleable','referenceType', 'reference', 'id');
    }


    public function delete()
    {
        $schedule = Schedule::where('reference',$this->id)
        ->where('typeId',ScheduleType::SHIFT_ID)
        ->exists();

        if($schedule){
            errScheduleAlreadyExist("cannot delete shift");
        }

        return parent::delete();
    }

}
