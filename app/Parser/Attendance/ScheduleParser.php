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

        return parent::first($data);
    }

    public static function getMapping($schedules)
    {
        $result = [];
        $employeeIndexMap = [];

        foreach ($schedules as $schedule) {
            $employeeId = $schedule->employee->id;

            if (!isset($employeeIndexMap[$employeeId])) {
                $result[] = [
                    'employee' => [
                        'id' => $employeeId,
                        'name' => $schedule->employee->name,
                    ],
                    'mapping' => []
                ];

                $employeeIndexMap[$employeeId] = count($result) - 1;
            }

            $result[$employeeIndexMap[$employeeId]]['mapping'][] = [
                'date' => $schedule->date,
                'schedule' => parent::brief($schedule->scheduleable)
            ];
        }
        return $result;
    }


}
