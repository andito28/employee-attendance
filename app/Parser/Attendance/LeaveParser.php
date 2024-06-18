<?php

namespace App\Parser\Attendance;

use GlobalXtreme\Parser\BaseParser;

class LeaveParser extends BaseParser
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
            'fromDate' => $data->fromDate,
            'toDate' => $data->toDate,
            'notes' => $data->notes,
            'employeeName' => $data->employee->name
        ];
    }

}
