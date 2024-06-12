<?php

namespace App\Models\Leave;

use App\Models\BaseModel;

class Leave extends BaseModel
{
    protected $table = 'leaves';
    protected $guarded = ['id'];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime'
    ];

    /** --- RELATIONSHIPS --- */

    public function schedules()
    {
        return $this->morphMany(Schedule::class,'scheduleable','scheduleableType', 'scheduleableId', 'id');
    }

}
