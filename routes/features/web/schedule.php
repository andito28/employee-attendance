<?php

use App\Services\Constant\Employee\RoleUser;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Attendance\ScheduleController;

$administrator = RoleUser::ADMINISTRATOR_ID;

Route::prefix("schedules")
    ->middleware(["auth.api","role:$administrator"])
    ->group(function () {
        Route::get('', [ScheduleController::class, 'get']);
        Route::post('', [ScheduleController::class, 'create']);
    });

