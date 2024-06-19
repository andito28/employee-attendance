<?php
namespace App\Services\Excel;

use App\Models\Attendance\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;

class GenerateTimesheetExcel implements FromCollection
{
    public function collection()
    {
        return Attendance::all();
    }
}