<?php
namespace App\Services\PDF;

use PDF;
use Carbon\Carbon;
use App\Models\Attendance\Timesheet;

class GenerateTimesheetPdf
{
    public function generate($request)
    {
        $mappedAttendances = Timesheet::getFilteredAttendances($request);

        $monthYear = $request->input('date', Carbon::now()->format('m/Y'));
        $date = Carbon::createFromFormat('m/Y', $monthYear);

        $data = [
            'title' => 'Report Timesheet',
            'date' => "Bulan ".$date->format('m').", Tahun ".$date->format('Y'),
            'attendances' =>  $mappedAttendances
        ];

        $pdf = PDF::loadView('pdf.report_timesheet', $data);
        return $pdf->download("timesheet_".$date->format('m').$date->format('Y')."pdf");
    }

}
