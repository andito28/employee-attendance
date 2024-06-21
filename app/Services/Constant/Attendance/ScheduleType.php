<?php

namespace App\Services\Constant\Attendance;

use App\Services\Constant\BaseIDName;

class ScheduleType extends BaseIDName
{
    const PUBLIC_HOLIDAY_ID = 1;
    const PUBLIC_HOLIDAY = 'Public Holiday';

    const WEEKLY_DAY_OFF_ID = 2;
    const WEEKLY_DAY_OFF = 'Weekly Day Off';

    const LEAVE_ID = 3;
    const LEAVE = 'Leave';

    const SHIFT_ID = 4;
    const SHIFT = 'Shift';

    const OPTION = [
        self::PUBLIC_HOLIDAY_ID => self::PUBLIC_HOLIDAY,
        self::WEEKLY_DAY_OFF_ID => self::WEEKLY_DAY_OFF,
        self::LEAVE_ID => self::LEAVE,
        self::SHIFT_ID => self::SHIFT,
    ];

}
