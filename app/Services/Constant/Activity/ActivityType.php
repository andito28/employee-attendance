<?php

namespace App\Services\Constant\Activity;

use App\Services\Constant\BaseCodeName;

class ActivityType extends BaseCodeName
{
    const GENERAL = 'general';
    const COMPONENT = 'component';
    const COMPANY_OFFICE = 'company office';
    const DEPARTMENT = 'department';
    const OFFICE_DEPARTMENT = 'company office department';
    const EMPLOYEE = 'employee';
    const RESIGNATION = 'resignation';
    const SHIFT = 'shift';
    const PUBLIC_HOLIDAY = 'public holiday';
    const LEAVE = 'leave';
    const SCHEDULE = 'schedule';

    const OPTION = [
        self::GENERAL,
        self::COMPONENT,
        self::COMPANY_OFFICE,
        self::DEPARTMENT,
        self::OFFICE_DEPARTMENT,
        self::EMPLOYEE,
        self::RESIGNATION,
        self::SHIFT,
        self::PUBLIC_HOLIDAY,
        self::LEAVE,
        self::SCHEDULE,
    ];

}
