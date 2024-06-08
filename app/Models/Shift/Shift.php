<?php

namespace App\Models\Shift;

use App\Models\BaseModel;
use App\Parser\Shift\ShiftParser;
use App\Models\Shift\Traits\HasActivityShiftProperty;

class Shift extends BaseModel
{
    use HasActivityShiftProperty;

    // protected $table = '';
    protected $guarded = ['id'];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime'
    ];

    public $parserClass = ShiftParser::class;

     /** --- SCOPES --- */

    public function scopeFilter($query, $request)
    {
        return $query->where(function ($query) use ($request) {

            if ($this->hasSearch($request)) {
                $query->where('code', 'LIKE', "%$request->search%")
                    ->orWhere('name', 'LIKE', "%$request->search%");
            }

        });
    }

}
