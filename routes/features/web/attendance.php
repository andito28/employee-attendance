<?php

use App\Services\Constant\Employee\RoleUser;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Attendance\TimesheetController;
use App\Http\Controllers\Web\Attendance\ShiftController;
use App\Http\Controllers\Web\Attendance\PublicHolidayController;
use App\Http\Controllers\Web\Attendance\LeaveController;
use App\Http\Controllers\Web\Attendance\ScheduleController;

$administrator = RoleUser::ADMINISTRATOR_ID;
$employee = RoleUser::EMPLOYEE_ID;

Route::prefix("attendances")
    ->middleware(["auth.api"])
    ->group(function () use ($administrator, $employee) {

        // Shift
        Route::prefix("shifts")
            ->middleware(["role:$administrator"])
            ->group(function () {
                Route::get('', [ShiftController::class, 'get']);
                Route::post('', [ShiftController::class, 'create']);
                Route::put('{id}', [ShiftController::class, 'update']);
                Route::delete('{id}', [ShiftController::class, 'delete']);
            });

        // Public holiday
        Route::prefix("public-holidays")
            ->middleware(["role:$administrator"])
            ->group(function () {
                Route::get('', [PublicHolidayController::class, 'get']);
                Route::post('', [PublicHolidayController::class, 'create']);
                Route::put('{id}', [PublicHolidayController::class, 'update']);
                Route::delete('{id}', [PublicHolidayController::class, 'delete']);
                Route::post('{id}/assign-schedule', [PublicHolidayController::class, 'assignSchedule']);
            });

        // Leaves
        Route::prefix("leaves")
            ->group(function () use ($administrator, $employee) {
                Route::middleware("role:$administrator")->group(function () {
                    Route::patch('{id}/approve', [LeaveController::class, 'approveLeave']);
                });
                Route::middleware("role:$administrator,$employee")->group(function () {
                    Route::get('', [LeaveController::class, 'get']);
                    Route::post('', [LeaveController::class, 'create']);
                    Route::delete('{id}', [LeaveController::class, 'delete']);
                });
            });

        // Schedule
        Route::prefix("schedules")
            ->middleware(["role:$administrator"])
            ->group(function () {
                Route::get('', [ScheduleController::class, 'get']);
                Route::post('', [ScheduleController::class, 'create']);
            });

        // Timesheet
        Route::prefix("timesheets")
            ->group(function () use ($administrator, $employee) {
                Route::middleware("role:$administrator")->group(function () {
                    Route::get('', [TimesheetController::class, 'get']);
                    Route::get('generate-excel', [TimesheetController::class, 'generateAttendanceExcel']);
                    Route::get('generate-pdf', [TimesheetController::class, 'generateAttendancePdf']);
                });
                Route::middleware("role:$administrator,$employee")->group(function () {
                    Route::post('clock-in', [TimesheetController::class, 'clockIn']);
                    Route::post('clock-out', [TimesheetController::class, 'clockOut']);
                });
            });

    });
