<?php

namespace App\Models\Component;

use App\Models\BaseModel;
use App\Models\Component\Department;
use App\Parser\Component\CompanyOfficeParser;
use App\Models\Component\Traits\HasActivityCompanyOfficeProperty;

class CompanyOffice extends BaseModel
{
    use HasActivityCompanyOfficeProperty;

    protected $table = 'component_company_offices';
    protected $guarded = ['id'];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime'
    ];

    public $parserClass = CompanyOfficeParser::class;

       /** --- RELATIONSHIPS --- */

    public function officeDepartments()
    {
        return $this->hasMany(CompanyOfficeDepartment::class, 'companyOfficeId');
    }

    public function departments()
    {
        return $this->belongsToMany(
            Department::class,
            'component_company_office_departments',
            'companyOfficeId',
            'departmentId'
        )->whereNull('component_company_office_departments.deletedAt');
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

    public function delete()
    {
        $this->officeDepartments()->delete();
        return parent::delete();
    }

    public function getDepartmentMappings()
    {
        $departments = Department::all();
        $officeDepartmentIds = $this->officeDepartments()->pluck('departmentId')->toArray();
        $mappings = [];

        foreach ($departments as $department) {
            $assigned = in_array($department->id, $officeDepartmentIds);
            $mappings[] = [
                'id' => $department->id,
                'assigned' => $assigned,
                'name' => $department->name,
            ];
        }

        return $mappings;
    }

}
