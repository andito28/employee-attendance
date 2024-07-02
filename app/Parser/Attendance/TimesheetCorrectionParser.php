<?php

namespace App\Parser\Attendance;

use GlobalXtreme\Parser\BaseParser;
use App\Services\Constant\Attendance\TimesheetStatus;
use App\Services\Constant\Attendance\TimesheetCorrectionApproval;

class TimesheetCorrectionParser extends BaseParser
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
            'employee' => [
                'id' => $data->employee->id,
                'name' => $data->employee->name
            ],
            'date'=> $data->date,
            'clockIn' => $data->clockIn,
            'clockOut' => $data->clockOut,
            'status' => TimesheetStatus::display($data->statusId),
            'approval' => TimesheetCorrectionApproval::display($data->approvalId),
            'notes' => $data->notes,
            'approvedBy' => $data->approvedByName
        ];
    }

}
