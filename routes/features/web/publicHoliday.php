<?php

use App\Services\Constant\RoleUser;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Attendance\PublicHolidayController;

$administrator = RoleUser::ADMINISTRATOR_ID;

Route::prefix("public-holidays")
    ->middleware(["auth.api","role:$administrator"])
    ->group(function () {
        Route::get('', [PublicHolidayController::class, 'get']);
        Route::post('', [PublicHolidayController::class, 'create']);
        Route::put('{id}', [PublicHolidayController::class, 'update']);
        Route::delete('{id}', [PublicHolidayController::class, 'delete']);
        Route::post('{id}/assign-schedule', [PublicHolidayController::class, 'assignSchedule']);
    });
