<?php

namespace App\Models\Leave;

use App\Models\BaseModel;
use App\Models\Employee\Employee;
use App\Parser\Leave\LeaveParser;
use App\Models\Leave\Traits\HasActivityLeaveProperty;

class Leave extends BaseModel
{
    use HasActivityLeaveProperty;

    protected $table = 'leaves';
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
        return $this->morphMany(Schedule::class,'scheduleable','scheduleableType', 'scheduleableId', 'id');
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


}
