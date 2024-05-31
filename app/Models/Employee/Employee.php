<?php

namespace App\Models\Employee;

use App\Models\BaseModel;

class Employee extends BaseModel
{
    // protected $table = '';
    protected $guarded = ['id'];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime'
    ];



    /** --- RELATIONSHIPS --- */

    public function user()
    {
        return $this->hasOne(Employee::class, 'employeeId');
    }

}
