<?php

namespace App\Services\Constant\Employee;

use App\Services\Constant\BaseIDName;

class RoleUser extends BaseIDName
{
    const ADMINISTRATOR_ID = 1;
    const ADMINISTRATOR = 'Administrator';

    const EMPLOYEE_ID = 2;
    const EMPLOYEE = 'Employee';

    const OPTION = [
        self::ADMINISTRATOR_ID => self::ADMINISTRATOR,
        self::EMPLOYEE_ID => self::EMPLOYEE,
    ];

}
