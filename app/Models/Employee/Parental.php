<?php

namespace App\Models\Employee;

use App\Models\BaseModel;
use App\Models\Employee\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Employee\Traits\HasActivityParentalProperty;

class Parental extends BaseModel
{
    use HasFactory;

    protected $table = 'employee_parents';
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
