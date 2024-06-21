<?php

namespace App\Models\Attendance;

use App\Models\BaseModel;
use App\Models\Attendance\Shift;
use App\Models\Employee\Employee;
use App\Models\Attendance\Traits\HasActivityAttendanceProperty;

class Attendance extends BaseModel
{
    use HasActivityAttendanceProperty;

    protected $table = 'attendance_timesheets';
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

    /** --- SCOPES --- */

    public function scopeFilterYearMonth($query, $request)
    {
        return $query->where(function ($query) use ($request) {

            return  $query->whereYear('createdAt', $request->year)
                    ->whereMonth('createdAt', $request->month);

        });
    }

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

}
