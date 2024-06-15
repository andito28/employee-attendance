<?php

namespace App\Models\Attendance;

use App\Models\BaseModel;
use App\Models\Shift\Shift;
use App\Models\Employee\Employee;
use App\Models\Attendance\Traits\HasActivityAttendanceProperty;

class Attendance extends BaseModel
{
    use HasActivityAttendanceProperty;

    // protected $table = '';
    protected $guarded = ['id'];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime'
    ];

    /** --- RELATIONSHIPS --- */
    public function employee(){
        return $this->belongsTo(Employee::class,'employeeId');
    }

    public function shift(){
        return $this->belongsTo(Shift::class,'shiftId');
    }

}
