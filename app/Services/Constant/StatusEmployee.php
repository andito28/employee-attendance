<?php

namespace App\Services\Constant;

use App\Services\Constant\BaseIDName;

class StatusEmployee extends BaseIDName
{
    const ACTIVE_ID = 1;
    const ACTIVE = 'Active';

    const RESIGNED_ID = 2;
    const RESIGNED = 'Resigned';

    const OPTION = [
        self::ACTIVE_ID => self::ACTIVE,
        self::RESIGNED_ID => self::RESIGNED,
    ];

}
