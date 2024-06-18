<?php

use App\Services\Constant\RoleUser;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Attendance\ShiftController;

$administrator = RoleUser::ADMINISTRATOR_ID;

Route::prefix("shifts")
    ->middleware(["auth.api","role:$administrator"])
    ->group(function () {

        Route::get('', [ShiftController::class, 'get']);
        Route::post('', [ShiftController::class, 'create']);
        Route::put('{id}', [ShiftController::class, 'update']);
        Route::delete('{id}', [ShiftController::class, 'delete']);

});

