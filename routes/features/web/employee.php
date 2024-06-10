<?php

use App\Services\Constant\RoleUser;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Employee\EmployeeController;

$administrator = RoleUser::ADMINISTRATOR_ID;
$employee = RoleUser::EMPLOYEE_ID;

Route::prefix("employees")
    ->middleware(["auth.api","role:$administrator"])
    ->group(function () {

        Route::get('', [EmployeeController::class, 'get']);
        Route::post('', [EmployeeController::class, 'create']);
        Route::post('{id}', [EmployeeController::class, 'update']);
        Route::delete('{id}', [EmployeeController::class, 'delete']);
        Route::patch('{id}/promote-admin', [EmployeeController::class, 'promoteToAdministrator']);
        Route::post('{id}/resignation', [EmployeeController::class, 'resignation']);
        Route::patch('{id}/resignation/reverse-status', [EmployeeController::class, 'reverseResignationStatus']);
});

Route::patch('employees/reset-password', [EmployeeController::class, 'resetPassword'])
->middleware(['auth.api', "role:$administrator,$employee"]);
