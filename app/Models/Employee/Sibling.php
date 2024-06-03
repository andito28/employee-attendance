<?php

namespace App\Models\Employee;

use App\Models\BaseModel;
use App\Models\Employee\Employee;
use App\Parser\Employee\SiblingParser;
use App\Models\Employee\Traits\HasActivitySiblingProperty;

class Sibling extends BaseModel
{
    use HasActivitySiblingProperty;

    // protected $table = '';
    protected $guarded = ['id'];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime'
    ];

    public $parserClass = SiblingParser::class;


     /** --- RELATIONSHIPS --- */

    public function employee()
    {
        return $this->belongsTo(Employee::class,'employeeId');
    }



}
