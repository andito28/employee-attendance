<?php

namespace App\Models\Schedule;

use App\Models\BaseModel;

class Schedule extends BaseModel
{
    // protected $table = '';
    protected $guarded = ['id'];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime'
    ];


    /** --- RELATIONSHIPS --- */
    public function scheduleable()
    {
        return $this->morphTo();
    }

}
