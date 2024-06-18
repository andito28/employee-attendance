<?php

namespace App\Parser\Attendance;

use GlobalXtreme\Parser\BaseParser;

class PublicHolidayParser extends BaseParser
{
    /**
     * @param $data
     *
     * @return array|null
     */
    public static function first($data)
    {
        if (!$data) {
            return null;
        }

        return parent::first($data);
    }

}
