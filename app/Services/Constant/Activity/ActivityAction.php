<?php

namespace App\Services\Constant\Activity;

use App\Services\Constant\BaseCodeName;

class ActivityAction extends BaseCodeName
{
    const CREATE = 'create';
    const UPDATE = 'update';
    const DELETE = 'delete';

    const OPTION = [
        self::CREATE,
        self::UPDATE,
        self::DELETE,
    ];

}
