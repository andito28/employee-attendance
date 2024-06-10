<?php

namespace App\Models\Shift;

use App\Models\BaseModel;
use App\Models\Employee\Employee;
use App\Models\Schedule\Schedule;
use App\Parser\Shift\ShiftParser;
use App\Models\Attendance\Attendance;
use App\Models\Shift\Traits\HasActivityShiftProperty;

class Shift extends BaseModel
{
    use HasActivityShiftProperty;

    // protected $table = '';
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
        return $this->morphMany(Schedule::class, 'scheduleable', 'scheduleableType', 'scheduleableId', 'id');
    }

}
