<?php

namespace App\Models\PublicHoliday;

use App\Models\BaseModel;
use App\Models\Schedule\Schedule;
use App\Services\Constant\ScheduleType;
use App\Parser\PublicHoliday\PublicHolidayParser;
use App\Models\PublicHoliday\Traits\HasActivityPublicHolidayActivityProperty;

class PublicHoliday extends BaseModel
{
    use HasActivityPublicHolidayActivityProperty;

    protected $table = 'public_holidays';
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
        return $this->morphMany(Schedule::class, 'scheduleable', 'scheduleableType', 'scheduleableId', 'id');
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
        Schedule::where('scheduleableId',$this->id)
        ->where('typeId',ScheduleType::PUBLIC_HOLIDAY_ID)
        ->delete();

        return parent::delete();
    }

}
