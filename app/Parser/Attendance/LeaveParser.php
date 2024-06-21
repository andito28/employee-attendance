<?php

namespace App\Parser\Attendance;

use GlobalXtreme\Parser\BaseParser;
use App\Services\Constant\Attendance\LeaveStatus;

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
            'status' => LeaveStatus::display($data->statusId),
            'employeeName' => $data->employee->name
        ];
    }

}
