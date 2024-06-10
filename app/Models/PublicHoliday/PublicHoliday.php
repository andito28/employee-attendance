<?php

namespace App\Models\PublicHoliday;

use App\Models\BaseModel;

class PublicHoliday extends BaseModel
{
    use HasActivityPublicHolidayActivityProperty;
    // protected $table = '';
    protected $guarded = ['id'];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime'
    ];

    public $parserClass = PublicHolidayParser::class;

}
