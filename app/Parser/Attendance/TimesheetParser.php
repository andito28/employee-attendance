<?php

namespace App\Parser\Attendance;

use GlobalXtreme\Parser\BaseParser;
use App\Services\Constant\Attendance\TimesheetStatus;

class TimesheetParser extends BaseParser
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
            'date' => $data->date,
            'clockIn' => $data->clockIn,
            'clockOut' => $data->clockOut,
            'status' => TimesheetStatus::display($data->statusId),
            'employee' => [
                'id' => $data->employee->id,
                'name' => $data->employee->name
            ],
            'shift' => [
                'id' => $data->shift->id,
                'name' => $data->shift->name
            ]
        ];
    }

    public static function attendanceLog($dataAttendance)
    {
        if (!$dataAttendance) {
            return null;
        }

        $data = [];
        foreach($dataAttendance as $value){
            $data[] = [
                'date' => $value->date,
                'clockIn' => $value->clockIn,
                'clockOut' => $value->clockOut,
            ];
        }

        return $data;
    }

}
