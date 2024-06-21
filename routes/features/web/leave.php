<?php

use App\Services\Constant\Employee\RoleUser;
use App\Http\Controllers\Web\Attendance\LeaveController;

$administrator = RoleUser::ADMINISTRATOR_ID;
$employee = RoleUser::EMPLOYEE_ID;

Route::prefix("leaves")
    ->middleware(["auth.api"])
    ->group(function () use ($administrator, $employee) {

         // Routes accessible only by administrators
        Route::middleware("role:$administrator")->group(function () {
            Route::patch('{id}/approve', [LeaveController::class, 'approveLeave']);
        });

        // Routes accessible only by administrators and employee
        Route::middleware("role:$administrator,$employee")->group(function () {
            Route::get('', [LeaveController::class, 'get']);
            Route::post('', [LeaveController::class, 'create']);
            Route::delete('{id}', [LeaveController::class, 'delete']);
        });

    });
