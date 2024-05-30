<?php

namespace App\Models\v1\Component;

use App\Models\BaseModel;
use App\Parser\Component\DepartmentParser;
use App\Models\v1\Component\Traits\HasActivityDepartmentProperty;

class Department extends BaseModel
{
    use HasActivityDepartmentProperty;

    public $parserClass = DepartmentParser::class;
    // protected $table = '';
    protected $guarded = ['id'];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime'
    ];

}
