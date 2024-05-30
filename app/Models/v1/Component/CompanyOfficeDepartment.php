<?php

namespace App\Models\v1\Component;

use App\Models\BaseModel;
use App\Parser\Component\OfficeDepartmentParser;
use App\Models\v1\Component\Traits\HasActivityOfficeDepartmentProperty;

class CompanyOfficeDepartment extends BaseModel
{

    use HasActivityOfficeDepartmentProperty;

    public $parserClass = OfficeDepartmentParser::class;
    // protected $table = '';
    protected $guarded = ['id'];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime'
    ];

}
