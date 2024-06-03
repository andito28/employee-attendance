<?php

use App\Services\Constant\RoleUser;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Employee\EmployeeController;

// $administrator = RoleUser::ADMINISTRATOR_ID;
// middleware(["auth.api","role:$administrator"])

Route::prefix("employees")
    ->group(function () {

        // employee
        Route::get('', [EmployeeController::class, 'get']);
        Route::post('', [EmployeeController::class, 'create']);
        Route::put('{id}', [EmployeeController::class, 'update']);
        Route::delete('{id}', [EmployeeController::class, 'delete']);

});
