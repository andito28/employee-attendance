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

        return parent::first($data);

    }


    public static function getEmployee($data)
    {
        if (!$data) {
            return null;
        }

        $datas = [];
        foreach($data as $value){

            $roleTrash = $value->user()->withTrashed()->first()->roleId;
            $emailTrash =  $value->user()->withTrashed()->first()->email;

            $datas[] = [
            'id' => $value->id,
            'role' => $value->user ? RoleUser::display($value->user->roleId) : RoleUser::display($roleTrash),
            'name' => $value->name,
            'number' => $value->number,
            'email' => $value->user ? $value->user->email: $emailTrash,
            'status' => StatusEmployee::display($value->statusId),
            'photo' => Storage::url($value->photo),
            'phone' => $value->phone,
            'address' => $value->address,
            'companyOffice' => CompanyOfficeParser::brief($value->companyOffice),
            'department' => DepartmentParser::brief($value->department),
            'parent' => ParentalParser::brief($value->parental),
            'siblings' => SiblingParser::briefs($value->siblings)
            ];
        }

        return $datas;
    }

}
