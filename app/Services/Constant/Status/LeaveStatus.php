<?php

namespace App\Services\Constant\Status;

use App\Services\Constant\BaseIDName;

class LeaveStatus extends BaseIDName
{
    const PROCESSED_ID = 1;
    const PROCESSED = 'Processed';

    const ACCEPTED_ID = 2;
    const ACCEPTED = 'Accepted';

    const REJECTED_ID = 3;
    const REJECTED = 'Rejected';

    const OPTION = [
        self::PROCESSED_ID => self::PROCESSED,
        self::ACCEPTED_ID => self::ACCEPTED,
        self::REJECTED_ID => self::REJECTED,
    ];


}
