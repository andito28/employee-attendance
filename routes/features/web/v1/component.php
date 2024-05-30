<?php

use App\Http\Controllers\Web\v1\Component\CompanyOfficeController;
use Illuminate\Support\Facades\Route;

Route::prefix("components")
    ->group(function () {

        // Company Offices
        Route::prefix("company-offices")
            ->group(function () {

                Route::get('', [CompanyOfficeController::class, 'get']);
                Route::post('', [CompanyOfficeController::class, 'create']);
                Route::put('{id}', [CompanyOfficeController::class, 'update']);
                Route::delete('{id}', [CompanyOfficeController::class, 'delete']);
            });

    });
