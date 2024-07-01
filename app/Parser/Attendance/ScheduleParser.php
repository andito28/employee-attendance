<?php

namespace App\Parser\Attendance;

use App\Models\Attendance\Leave;
use App\Models\Attendance\Shift;
use GlobalXtreme\Parser\BaseParser;
use App\Services\Constant\Attendance\ScheduleType;
use App\Models\Attendance\PublicHoliday;

class ScheduleParser extends BaseParser
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
            'type' => ScheduleType::display($data->typeId),
            'date' => $data->date,
            'employee' => [
                'id' => $data->employee->id,
                'name' => $data->employee->name
            ],
            'schduleable' =>$data->scheduleable
        ];

    }


}
