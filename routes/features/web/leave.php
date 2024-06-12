<?php

use App\Services\Constant\RoleUser;
use App\Http\Controllers\Web\Leave\LeaveController;


$administrator = RoleUser::ADMINISTRATOR_ID;
$employee = RoleUser::EMPLOYEE_ID;

Route::prefix("leaves")
    ->middleware(["auth.api","role:$administrator"])
    ->group(function () {

        Route::get('', [LeaveController::class, 'get']);
        Route::post('', [LeaveController::class, 'create']);

});

