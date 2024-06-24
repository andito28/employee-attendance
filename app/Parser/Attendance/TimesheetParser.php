<?php

namespace App\Parser\Attendance;

use Carbon\Carbon;
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

    public static function attendanceLog($dataAttendance,$requestDate)
    {
        if (!$dataAttendance) {
            return null;
        }

        $data = [];
        $datesInMonth = [];

        $monthYear = $requestDate->input('date', Carbon::now()->format('m/Y'));
        $date = Carbon::createFromFormat('m/Y', $monthYear);
        $year = $date->year;
        $month = $date->month;

        $daysInMonth = Carbon::now()->setYear($year)->setMonth($month)->daysInMonth;
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($year, $month, $day)->format('Y-m-d');
            $datesInMonth[$date] = [
                'date' => $date,
                'clockIn' => '-',
                'clockOut' => '-',
            ];
        }

        foreach ($dataAttendance as $value) {
            $date = Carbon::parse($value->date)->format('Y-m-d');
            $datesInMonth[$date] = [
                'date' => $date,
                'clockIn' => $value->clockIn ? Carbon::parse($value->clockIn)->format('H:i') : '-',
                'clockOut' => $value->clockOut ? Carbon::parse($value->clockOut)->format('H:i') : '-',
            ];
        }

        $data = array_values($datesInMonth);
        return $data;
    }


}
