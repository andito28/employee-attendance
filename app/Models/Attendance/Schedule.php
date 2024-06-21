<?php

namespace App\Models\Attendance;

use App\Models\BaseModel;
use App\Models\Employee\Employee;
use App\Parser\Attendance\ScheduleParser;
use App\Models\Attendance\Traits\HasActivityScheduleProperty;


class Schedule extends BaseModel
{
    use HasActivityScheduleProperty;

    protected $table = 'attendance_schedules';
    protected $guarded = ['id'];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime'
    ];

    public $parserClass = ScheduleParser::class;


    /** --- RELATIONSHIPS --- */
    public function scheduleable()
    {
        return $this->morphTo('scheduleable', 'referenceType', 'reference');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class,'employeeId');
    }

}
