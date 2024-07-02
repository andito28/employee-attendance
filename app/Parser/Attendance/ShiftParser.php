<?php

namespace App\Parser\Attendance;

use GlobalXtreme\Parser\BaseParser;

class ShiftParser extends BaseParser
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
            'startTime' => $data->startTime,
            'endTime' => $data->endTime,
            'createdBy' => $data->createdByName
        ];
    }

}
