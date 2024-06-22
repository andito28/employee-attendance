<?php

namespace App\Models\Attendance;

use Carbon\Carbon;
use App\Models\BaseModel;
use App\Models\Attendance\Shift;
use App\Models\Employee\Employee;
use App\Parser\Attendance\TimesheetParser;
use App\Models\Attendance\Traits\HasActivityAttendanceProperty;

class Timesheet extends BaseModel
{
    use HasActivityAttendanceProperty;

    protected $table = 'attendance_timesheets';
    protected $guarded = ['id'];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime'
    ];

    public $parserClass = TimesheetParser::class;

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

            $monthYear = $request->input('date', Carbon::now()->format('m/Y'));
            $date = Carbon::createFromFormat('m/Y', $monthYear);

            $month = $date->month;
            $year = $date->year;

            return  $query->whereYear('date', $year)
                    ->whereMonth('date',  $month);

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
