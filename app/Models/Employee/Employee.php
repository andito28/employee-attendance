<?php

namespace App\Models\Employee;

use App\Models\BaseModel;
use App\Models\User\User;
use App\Models\Employee\Sibling;
use App\Models\Employee\Parental;
use App\Models\Employee\Resignation;
use App\Parser\Employee\EmployeeParser;
use App\Models\Employee\Traits\HasActivityEmployeeProperty;

class Employee extends BaseModel
{
    use HasActivityEmployeeProperty;

    // protected $table = '';
    protected $guarded = ['id'];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime'
    ];

    public $parserClass = EmployeeParser::class;


    /** --- RELATIONSHIPS --- */

    public function user()
    {
        return $this->hasOne(User::class, 'employeeId');
    }

    public function parental()
    {
        return $this->hasOne(Parental::class, 'employeeId');
    }

    public function siblings()
    {
        return $this->hasMany(Sibling::class, 'employeeId');
    }

    public function resignations()
    {
        return $this->hasMany(Resignation::class, 'employeeId');
    }

}
