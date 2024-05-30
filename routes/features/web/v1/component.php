<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\web\v1\component\DepartmentController;
use App\Http\Controllers\Web\v1\Component\CompanyOfficeController;

Route::prefix("components")
    ->group(function () {

        // Company Offices
        Route::prefix("company-offices")
            ->group(function () {

                Route::get('', [CompanyOfficeController::class, 'get']);
                Route::post('', [CompanyOfficeController::class, 'create']);
                Route::put('{id}', [CompanyOfficeController::class, 'update']);
                Route::delete('{id}', [CompanyOfficeController::class, 'delete']);
                Route::get('{id}/departments', [CompanyOfficeController::class, 'getDepartment']);
                Route::post('{id}/departments/mapping', [CompanyOfficeController::class, 'mappingOfficeDepartment']);
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
