<?php

namespace App\Models\Component;

use App\Models\BaseModel;
use App\Parser\Component\DepartmentParser;
use App\Models\Component\CompanyOfficeDepartment;
use App\Models\Component\Traits\HasActivityDepartmentProperty;

class Department extends BaseModel
{
    use HasActivityDepartmentProperty;

    protected $table = 'component_departments';
    protected $guarded = ['id'];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime'
    ];

    public $parserClass = DepartmentParser::class;

     /** --- RELATIONSHIPS --- */

    public function companyOfficeDepartments()
    {
        return $this->hasMany(CompanyOfficeDepartment::class, 'departmentId');
    }

     /** --- FUNCTIONS --- */

    public function delete(){

        $hasCompanyOfficeDepartments = $this->companyOfficeDepartments()->exists();
        if ($hasCompanyOfficeDepartments) {
            errComponentDepartmentExists();
        }

        return parent::delete();
    }

}
