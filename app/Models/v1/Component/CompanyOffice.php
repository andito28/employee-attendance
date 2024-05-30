<?php

namespace App\Models\v1\Component;

use App\Models\BaseModel;
use App\Models\v1\Component\Department;
use App\Parser\Component\CompanyOfficeParser;
use App\Models\v1\Component\CompanyOfficeDepartment;
use App\Models\v1\Component\Traits\HasActivityCompanyOfficeProperty;

class CompanyOffice extends BaseModel
{
    use HasActivityCompanyOfficeProperty;
    public $parserClass = CompanyOfficeParser::class;
    // protected $table = '';
    protected $guarded = ['id'];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime'
    ];

      /** --- RELATIONSHIPS --- */

    public function officeDepartments()
    {
        $this->hasMany(CompanyOfficeDepartment::class, 'companyOfficeId');
    }

    public function departments()
    {
        $this->belongsToMany(
            Department::class,
            'company_office_departments',
            'companyOfficeId',
            'departmentId'
        )->whereNull('company_office_departments.deletedAt');
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

}
