<?php

use App\Services\Constant\Employee\RoleUser;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Attendance\TimesheetController;


$administrator = RoleUser::ADMINISTRATOR_ID;
$employee = RoleUser::EMPLOYEE_ID;

Route::prefix("timesheets")
    ->middleware(["auth.api"])
    ->group(function () use ($administrator, $employee) {

        // Routes accessible only by administrators
        Route::middleware("role:$administrator")->group(function () {
            Route::get('', [TimesheetController::class, 'get']);
            Route::get('generate-excel', [TimesheetController::class, 'generateAttendanceExcel']);
            Route::get('generate-pdf', [TimesheetController::class, 'generateAttendancePdf']);
        });

        // Routes accessible only by administrators and employee
        Route::middleware("role:$administrator,$employee")->group(function () {
            Route::post('clock-in', [TimesheetController::class, 'clockIn']);
            Route::post('clock-out', [TimesheetController::class, 'clockout']);
        });

    });
