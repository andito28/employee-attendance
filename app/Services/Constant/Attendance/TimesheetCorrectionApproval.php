<?php

namespace App\Services\Constant\Attendance;

use App\Services\Constant\BaseIDName;

class TimesheetCorrectionApproval extends BaseIDName
{
    const PENDING_ID = 1;
    const PENDING = 'Pending';

    const APPROVED_ID = 2;
    const APPROVED = 'Approved';

    const DISAPPROVED_ID = 3;
    const DISAPPROVED = 'Disapproved';

    const OPTION = [
        self::PENDING_ID => self::PENDING,
        self::APPROVED_ID => self::APPROVED,
        self::DISAPPROVED_ID => self::DISAPPROVED,
    ];

}
