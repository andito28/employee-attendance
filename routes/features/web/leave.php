<?php

use App\Services\Constant\RoleUser;
use App\Http\Controllers\Web\Leave\LeaveController;


$administrator = RoleUser::ADMINISTRATOR_ID;
$employee = RoleUser::EMPLOYEE_ID;

Route::group([
    'prefix' => 'leaves',
    'middleware' => ["auth.api", "role:$administrator,$employee"],
], function () {
    Route::get('', [LeaveController::class, 'get']);
    Route::post('', [LeaveController::class, 'create']);
    Route::delete('{id}', [LeaveController::class, 'delete']);
});


Route::patch('leaves/{id}/approve', [LeaveController::class, 'approveLeave'])
->middleware(['auth.api', "role:$administrator"]);
