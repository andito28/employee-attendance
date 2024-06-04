<?php

use App\Services\Constant\RoleUser;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Employee\EmployeeController;

$administrator = RoleUser::ADMINISTRATOR_ID;

Route::prefix("employees")
    ->middleware(["auth.api","role:$administrator"])
    ->group(function () {

        Route::get('', [EmployeeController::class, 'get']);
        Route::post('', [EmployeeController::class, 'create']);
        Route::patch('{id}', [EmployeeController::class, 'update']);
        Route::delete('{id}', [EmployeeController::class, 'delete']);
        Route::patch('{id}/promote-admin', [EmployeeController::class, 'promoteToAdministrator']);
        Route::delete('{id}/attendances}', [EmployeeController::class, 'deleteAttendances']);
        Route::post('{id}/resignation', [EmployeeController::class, 'resignation']);

});
