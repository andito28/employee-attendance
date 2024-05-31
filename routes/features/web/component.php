<?php

use App\Services\Constant\RoleUser;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\web\component\DepartmentController;
use App\Http\Controllers\Web\Component\CompanyOfficeController;

$administrator = RoleUser::ADMINISTRATOR_ID;

Route::middleware(["auth.api","checkRole:$administrator"])
    ->prefix("components")
    ->group(function () {

        // Company Offices
        Route::prefix("company-offices")
            ->group(function () {

                Route::get('', [CompanyOfficeController::class, 'get']);
                Route::post('', [CompanyOfficeController::class, 'create']);
                Route::put('{id}', [CompanyOfficeController::class, 'update']);
                Route::delete('{id}', [CompanyOfficeController::class, 'delete']);
                Route::get('{id}/departments', [CompanyOfficeController::class, 'getDepartment']);
                Route::post('{id}/departments/mapping', [CompanyOfficeController::class, 'saveOfficeDepartmentMapping']);
            });

        // Departments
        Route::prefix("departments")
        ->group(function () {

            Route::get('', [DepartmentController::class, 'get']);
            Route::post('', [DepartmentController::class, 'create']);
            Route::put('{id}', [DepartmentController::class, 'update']);
            Route::delete('{id}', [DepartmentController::class, 'delete']);
        });

});
