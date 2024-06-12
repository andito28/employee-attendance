<?php

namespace App\Models\Employee;

use App\Models\BaseModel;
use App\Models\Employee\Employee;
use App\Parser\Employee\SiblingParser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Employee\Traits\HasActivitySiblingProperty;

class Sibling extends BaseModel
{
    use HasFactory;

    protected $table = 'employee_siblings';
    protected $guarded = ['id'];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime'
    ];

     /** --- RELATIONSHIPS --- */

    public function employee()
    {
        return $this->belongsTo(Employee::class,'employeeId');
    }



}
