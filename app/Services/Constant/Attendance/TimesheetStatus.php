<?php

namespace App\Services\Constant\Attendance;

use App\Services\Constant\BaseIDName;

class TimesheetStatus extends BaseIDName
{
    const NO_CLOCK_IN_ID = 1;
    const NO_CLOCK_IN = 'No Clock-In';

    const NO_CLOCK_OUT_ID = 2;
    const NO_CLOCK_OUT = 'No Clock-Out';

    const ALPHA_ID = 3;
    const ALPHA = 'Alpha';

    const EARLY_ID = 4;
    const EARLY = 'Early';

    const LATE_ID = 5;
    const LATE = 'Late';

    const VALID_ID = 6;
    const VALID = 'Valid';

    const NOT_STATUS_ID = 7;
    const NOT_STATUS = 'Not Status';

    const OPTION = [
        self::NO_CLOCK_IN_ID => self::NO_CLOCK_IN,
        self::NO_CLOCK_OUT_ID => self::NO_CLOCK_OUT,
        self::ALPHA_ID => self::ALPHA,
        self::EARLY_ID => self::EARLY,
        self::LATE_ID => self::LATE,
        self::VALID_ID => self::VALID,
        self::NOT_STATUS_ID => self::NOT_STATUS,
    ];

}
