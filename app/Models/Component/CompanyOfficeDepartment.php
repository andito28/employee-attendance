<?php

namespace App\Models\Component;

use App\Models\BaseModel;
use App\Parser\Component\OfficeDepartmentParser;
use App\Models\Component\Traits\HasActivityOfficeDepartmentProperty;

class CompanyOfficeDepartment extends BaseModel
{
    use HasActivityOfficeDepartmentProperty;

    // protected $table = '';
    protected $guarded = ['id'];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime'
    ];

    public $parserClass = OfficeDepartmentParser::class;

}
