<?php

namespace App\Models\Employee;

use App\Models\BaseModel;
use App\Models\User\User;
use App\Models\Employee\Sibling;
use App\Models\Employee\Parental;
use App\Models\Component\Department;
use App\Models\Employee\Resignation;
use App\Models\Component\CompanyOffice;
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

    public function companyOffice()
    {
        return $this->belongsTo(CompanyOffice::class, 'companyOfficeId');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'departmentId');
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


     /** --- FUNCTIONS --- */

    public function delete(){

        $this->user()->delete();
        $this->parental()->delete();
        $this->siblings()->delete();

        return parent::delete();

    }

}
