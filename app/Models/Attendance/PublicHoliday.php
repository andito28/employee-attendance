<?php

namespace App\Models\Attendance;

use App\Models\BaseModel;
use App\Parser\Attendance\PublicHolidayParser;
use App\Services\Constant\Attendance\ScheduleType;
use App\Models\Attendance\Traits\HasActivityPublicHolidayActivityProperty;

class PublicHoliday extends BaseModel
{
    use HasActivityPublicHolidayActivityProperty;

    protected $table = 'attendance_public_holidays';
    protected $guarded = ['id'];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime'
    ];

    public $parserClass = PublicHolidayParser::class;

    /** --- RELATIONSHIPS --- */

    public function schedules()
    {
        return $this->morphMany(Schedule::class, 'scheduleable','referenceType', 'reference', 'id');
    }

    /** --- SCOPES --- */

    public function scopeFilter($query, $request)
    {
        return $query->where(function ($query) use ($request) {

            if ($this->hasSearch($request)) {
                $query->where('name', 'LIKE', "%$request->search%");
            }

        });
    }

    /** --- FUNCTIONS --- */

    public function delete()
    {
        $publicHolidaySchedule = Schedule::where('reference',$this->id)
        ->where('typeId',ScheduleType::PUBLIC_HOLIDAY_ID)
        ->exists();

        if($publicHolidaySchedule){
            errPublicHolidayIsAssign('Cannot delete public holiday');
        }
        return parent::delete();
    }

}
