<?php

namespace App\Models\Schedule;

use App\Models\BaseModel;
use App\Models\Employee\Employee;
use App\Parser\Schedule\ScheduleParser;
use App\Models\Schedule\Traits\HasActivityScheduleProperty;

class Schedule extends BaseModel
{
    use HasActivityScheduleProperty;
    // protected $table = '';
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
        return $this->morphTo();
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class,'employeeId');
    }

}
