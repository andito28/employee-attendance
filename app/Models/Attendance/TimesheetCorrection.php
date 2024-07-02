<?php

namespace App\Models\Attendance;

use App\Models\BaseModel;
use App\Models\Employee\Employee;
use App\Parser\Attendance\TimesheetCorrectionParser;
use App\Models\Attendance\Traits\HasActivityTimesheetCorrectionActivityProperty;

class TimesheetCorrection extends BaseModel
{
    use HasActivityTimesheetCorrectionActivityProperty;

    protected $table = 'attendance_timesheet_corrections';
    protected $guarded = ['id'];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime'
    ];

    public $parserClass = TimesheetCorrectionParser::class;

     /** --- RELATIONSHIPS --- */
    public function employee(){
        return $this->belongsTo(Employee::class,'employeeId');
    }

}
