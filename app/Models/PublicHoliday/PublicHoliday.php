<?php

namespace App\Models\PublicHoliday;

use App\Models\BaseModel;
use App\Parser\PublicHoliday\PublicHolidayParser;
use App\Models\PublicHoliday\Traits\HasActivityPublicHolidayActivityProperty;

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


    /** --- SCOPES --- */

    public function scopeFilter($query, $request)
    {
        return $query->where(function ($query) use ($request) {

            if ($this->hasSearch($request)) {
                $query->where('name', 'LIKE', "%$request->search%");
            }

        });
    }

}
