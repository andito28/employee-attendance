<?php

namespace App\Models\Component;

use App\Models\BaseModel;
use App\Parser\Component\CompanyOfficeParser;
use App\Models\Component\Traits\HasActivityCompanyOfficeProperty;

class CompanyOffice extends BaseModel
{
    use HasActivityCompanyOfficeProperty;

    // protected $table = '';
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

     /** --- FUNCTIONS --- */

    public function delete(){

        $this->officeDepartments()->delete();
        return parent::delete();

    }

}