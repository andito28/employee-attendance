<?php

namespace App\Services\Constant\Attendance;

use App\Services\Constant\BaseIDName;

class LeaveStatus extends BaseIDName
{
    const PENDING_ID = 1;
    const PENDING = 'Pending';
    const APPROVE_ID = 2;
    const APPROVE = 'Approve';

    const OPTION = [
        self::PENDING_ID => self::PENDING,
        self::APPROVE_ID => self::APPROVE,
    ];

}
