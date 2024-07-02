<?php

namespace App\Parser\Employee;

use App\Services\Constant\Employee\RoleUser;
use GlobalXtreme\Parser\BaseParser;
use App\Parser\Employee\SiblingParser;
use App\Parser\Employee\ParentalParser;
use Illuminate\Support\Facades\Storage;
use App\Services\Constant\Employee\StatusEmployee;
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

        $result =  [
            'id' => $data->id,
            'role' => $data->user ? RoleUser::display($data->user->roleId) : null,
            'name' => $data->name,
            'number' => $data->number,
            'email' => $data->user ? $data->user->email : null,
            'photo' => Storage::url($data->photo),
            'phone' => $data->phone,
            'address' => $data->address,
            'companyOffice' => CompanyOfficeParser::brief($data->companyOffice),
            'department' => DepartmentParser::brief($data->department),
            'parent' => self::parent($data->parental),
            'siblings' => self::siblings($data->siblings),
            'status' => StatusEmployee::display($data->statusId),
            'createdBy' => $data->createdByName
        ];

        if ($data->statusId == StatusEmployee::RESIGNED_ID) {
            $result['resignation'] = ResignationParser::briefs($data->resignations);
        }

        return $result;
    }

    public static function parent($data)
    {
        if (!$data) {
            return null;
        }

        return [
            'id' => $data->id,
            'fatherName' => $data->fatherName,
            'fatherPhone' => $data->fatherPhone,
            'fatherEmail' => $data->fatherEmail,
            'motherName' => $data->motherName,
            'motherPhone' => $data->motherPhone,
            'motherEmail' => $data->motherEmail,
        ];
    }

    public static function siblings($data)
    {
        if (!$data) {
            return null;
        }

        return $data->map(function($value) {
            return [
                'id' => $value->id,
                'name' => $value->name,
                'email' => $value->email
            ];
        })->toArray();
    }

}
