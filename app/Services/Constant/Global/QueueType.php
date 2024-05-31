<?php

namespace App\Services\Constant\Global;

use App\Services\Constant\BaseCodeName;

class QueueType extends BaseCodeName
{
    const DEFAULT = 'default';
    const NOTIFICATION = 'notification';
    const EMAIL = 'email';
    const LOW = 'low';
    const PDF = 'pdf';
    const REPORT = 'report';

    const OPTION = [
        self::DEFAULT,
        self::NOTIFICATION,
        self::EMAIL,
        self::LOW,
        self::PDF,
        self::REPORT,
    ];

}
