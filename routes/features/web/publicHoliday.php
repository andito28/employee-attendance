<?php

use App\Services\Constant\RoleUser;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\PublicHoliday\PublicHolidayController;

Route::prefix("public-holidays")
    ->group(function () {

        Route::get('', [PublicHolidayController::class, 'get']);
        Route::post('', [PublicHolidayController::class, 'create']);
        Route::put('{id}', [PublicHolidayController::class, 'update']);
        Route::delete('{id}', [PublicHolidayController::class, 'delete']);

});
