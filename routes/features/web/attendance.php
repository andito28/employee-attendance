<?php

use App\Services\Constant\RoleUser;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Attendance\TimesheetController;


$administrator = RoleUser::ADMINISTRATOR_ID;
$employee = RoleUser::EMPLOYEE_ID;

Route::prefix("timesheets")
    ->middleware(["auth.api","role:$administrator,$employee"])
    ->group(function () {

        Route::post('clock-in', [TimesheetController::class, 'clockIn']);
        Route::post('clock-out', [TimesheetController::class, 'clockout']);
        Route::get('generate-excel', [TimesheetController::class, 'generateAttendanceExcel']);

});
