<?php

namespace App\Parser\Employee;

use App\Services\Constant\RoleUser;
use GlobalXtreme\Parser\BaseParser;
use App\Parser\Employee\SiblingParser;
use App\Parser\Employee\ParentalParser;
use Illuminate\Support\Facades\Storage;
use App\Parser\Component\DepartmentParser;
use App\Parser\Component\CompanyOfficeParser;

class EmployeeParser extends BaseParser
{
    /**
     * @param $data
     *
     * @return array|null
     */
    public static function first($data)
    {
        if (!$data) {
            return null;
        }

        return parent::first($data);

    }


    public static function getEmployee($data)
    {
        if (!$data) {
            return null;
        }

        return [
            'id' => $data->id,
            'role' => RoleUser::display($data->user->roleId),
            'name' => $data->name,
            'number' => $data->number,
            'email' => $data->user->email,
            'photo' => Storage::url($data->photo),
            'phone' => $data->phone,
            'address' => $data->address,
            'companyOffice' => CompanyOfficeParser::brief($data->companyOffice),
            'department' => DepartmentParser::brief($data->department),
            'parent' => ParentalParser::brief($data->parental),
            'siblings' => SiblingParser::briefs($data->siblings)
        ];
    }

}
