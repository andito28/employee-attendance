<?php

namespace App\Parser\Attendance;

use GlobalXtreme\Parser\BaseParser;
use App\Parser\Employee\EmployeeParser;

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

        return [
            'id' => $data->id,
            'name' => $data->name,
            'date' => $data->date,
            'createdBy' => $data->createdBy,
            'createdByName' => $data->createdByName,
        ];
    }

}
