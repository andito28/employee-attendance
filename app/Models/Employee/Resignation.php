<?php

namespace App\Models\Employee;

use App\Models\BaseModel;
use App\Models\Employee\Employee;
use App\Parser\Employee\ResignationParser;
use App\Models\Employee\Traits\HasActivityResignationProperty;


class Resignation extends BaseModel
{
    use HasActivityResignationProperty;

    protected $table = 'employee_resignations';
    protected $guarded = ['id'];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime'
    ];

    public $parserClass = ResignationParser::class;

     /** --- RELATIONSHIPS --- */

    public function employee()
    {
        return $this->belongsTo(Employee::class,'employeeId');
    }


}
