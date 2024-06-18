<?php

namespace App\Parser\Attendance;

use App\Models\Attendance\Leave;
use App\Models\Attendance\Shift;
use GlobalXtreme\Parser\BaseParser;
use App\Services\Constant\ScheduleType;
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
                'schedule' => self::getSchedule($schedule->typeId,$schedule->scheduleableId)
            ];
        }
        return $result;
    }

    public static function getSchedule($type,$reference)
    {
        $data = [];
        switch ($type) {
            case 1:
                $publicHoliday = PublicHoliday::find($reference);
                $data['type'] = ScheduleType::display($type);
                $data['name'] = $publicHoliday->name;
                break;

            case 2:
                $data['type'] = ScheduleType::display($type);
                break;

            case 3:
                $leave = Leave::find($reference);
                $data['type'] = ScheduleType::display($type);
                $data['notes'] = $leave->notes;
                break;

            case 4:
                $shift = Shift::find($reference);
                $data['type'] = ScheduleType::display($type);
                $data['name'] = $shift->name;
                $data['startTime'] = $shift->startTime;
                $data['endTime'] = $shift->endtime;
                break;

            default:
                return null;
        }

        return $data;

    }

}
