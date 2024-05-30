<?php

namespace App\Services\Constant\Type;

use App\Services\Constant\BaseIDName;

class LeaveType extends BaseIDName
{
    const ANNUAL_LEAVE_ID = 1;
    const ANNUAL_LEAVE = 'Annual Leave';

    const SICK_LEAVE_ID = 2;
    const SICK_LEAVE = 'Sick Leave';

    const PERSONAL_LEAVE_ID = 3;
    const PERSONAL_LEAVE = 'Personal Leave';

    const SPECIAL_LEAVE_ID = 4;
    const SPECIAL_LEAVE = 'Special Leave';

    const OPTION = [
        self::ANNUAL_LEAVE_ID => self::ANNUAL_LEAVE,
        self::SICK_LEAVE_ID => self::SICK_LEAVE,
        self::PERSONAL_LEAVE_ID => self::PERSONAL_LEAVE,
        self::SPECIAL_LEAVE_ID => self::SPECIAL_LEAVE,
    ];

}
