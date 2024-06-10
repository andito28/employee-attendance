<?php

namespace App\Parser\Employee;

use App\Services\Constant\RoleUser;
use GlobalXtreme\Parser\BaseParser;
use App\Parser\Employee\SiblingParser;
use App\Parser\Employee\ParentalParser;
use Illuminate\Support\Facades\Storage;
use App\Services\Constant\StatusEmployee;
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

        $roleTrash = $data->user()->withTrashed()->first()->roleId;
        $emailTrash =  $data->user()->withTrashed()->first()->email;

        return  [
            'id' => $data->id,
            'role' => $data->user ? RoleUser::display($data->user->roleId) : RoleUser::display($roleTrash),
            'name' => $data->name,
            'number' => $data->number,
            'email' => $data->user ? $data->user->email: $emailTrash,
            'status' => StatusEmployee::display($data->statusId),
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
